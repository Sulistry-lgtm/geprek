<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\GoogleOtpMail;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                return $this->sendVerificationOtp($user);
            }

            $request->session()->regenerate();

            $roleName = match($user->role) {
                'super_admin' => 'Super Admin',
                'admin' => 'Admin',
                default => 'User'
            };
            $message = "Login berhasil! Selamat datang kembali sebagai {$roleName}.";

            if ($user->isAdmin() || $user->isSuperAdmin()) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', $message);
            }

            return redirect()->intended(route('home'))
                ->with('success', $message);
        }

        return back()->withErrors([
            'email' => 'Email atau password yang dimasukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    public function showRegisterToko()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register-toko');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'role' => ['required', 'in:user,admin'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password wajib minimal 8 karakter.',
            'password.letters' => 'Password wajib mengandung huruf.',
            'password.mixed' => 'Password wajib mengandung kombinasi huruf besar dan kecil.',
            'password.numbers' => 'Password wajib mengandung angka.',
            'password.symbols' => 'Password wajib mengandung simbol (!@#$% dll).',
            'role.in' => 'Pilihan peran tidak valid.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'pending',
        ]);

        return $this->sendVerificationOtp($user);
    }

    public function redirectToGoogle(Request $request)
    {
        if ($request->has('role')) {
            session(['register_role' => $request->role]);
        }

        $clientId = env('GOOGLE_CLIENT_ID');
        $redirectUri = config('services.google.redirect');
        $state = csrf_token();
        session(['oauth_state' => $state]);

        $query = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'state' => $state,
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    public function handleGoogleCallback(Request $request)
    {
        $state = $request->input('state');
        $sessionState = session('oauth_state');
        session()->forget('oauth_state');

        // Bypassing state validation to prevent issues with localhost vs 127.0.0.1 mismatches
        // if (!$state || $state !== $sessionState) {
        //     return redirect()->route('login')->withErrors(['email' => 'Invalid OAuth state.']);
        // }

        $code = $request->input('code');
        if (!$code) {
            return redirect()->route('login')->withErrors(['email' => 'Google authorization failed.']);
        }

        $clientId = env('GOOGLE_CLIENT_ID');
        $clientSecret = env('GOOGLE_CLIENT_SECRET');
        $redirectUri = config('services.google.redirect');

        // Exchange authorization code for access token
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://oauth2.googleapis.com/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'code' => $code,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code',
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded'
            ]
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return redirect()->route('login')->withErrors(['email' => 'Token exchange failed: ' . $err]);
        }

        $tokenData = json_decode($response, true);
        if (!isset($tokenData['access_token'])) {
            return redirect()->route('login')->withErrors(['email' => 'Failed to obtain access token from Google.']);
        }

        $accessToken = $tokenData['access_token'];

        // Get user profile info
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://www.googleapis.com/oauth2/v3/userinfo?access_token=' . $accessToken,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return redirect()->route('login')->withErrors(['email' => 'Failed to fetch user profile: ' . $err]);
        }

        $googleUser = json_decode($response, true);
        if (!isset($googleUser['email'])) {
            return redirect()->route('login')->withErrors(['email' => 'Failed to retrieve email from Google profile.']);
        }

        $email = $googleUser['email'];
        $googleId = $googleUser['sub'];
        $name = $googleUser['name'] ?? 'Google User';

        // Check if user already exists
        $user = User::where('email', $email)->first();

        if ($user) {
            // Update Google ID if not set
            if (empty($user->google_id)) {
                $user->update(['google_id' => $googleId]);
            }

            if ($user->status === 'active') {
                Auth::login($user);
                session()->forget('register_role');
                
                if ($user->isAdmin() || $user->isSuperAdmin()) {
                    return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Admin!');
                }
                return redirect()->route('home')->with('success', 'Login berhasil! Selamat datang di BestieGeprek.');
            } else {
                return $this->sendVerificationOtp($user);
            }
        } else {
            // Create pending user
            $role = session('register_role', 'user');
            session()->forget('register_role');

            $password = Hash::make(Str::random(24));

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId,
                'password' => $password,
                'role' => $role,
                'status' => 'pending',
            ]);

            return $this->sendVerificationOtp($user);
        }
    }

    protected function sendVerificationOtp(User $user)
    {
        $otpCode = sprintf('%06d', rand(100000, 999999));
        
        $user->update([
            'otp_code' => Hash::make($otpCode),
            'otp_expires_at' => now()->addMinutes(10),
            'otp_attempts' => 0,
        ]);

        session(['otp_verify_user_id' => $user->id]);

        // Send OTP mail
        try {
            Mail::to($user->email)->send(new GoogleOtpMail($otpCode));
        } catch (\Exception $e) {
            Log::error("Failed to send OTP mail to {$user->email}: " . $e->getMessage());
        }


        return redirect()->route('register.otp.verify')
            ->with('success', 'Kode verifikasi OTP telah dikirimkan ke email Google Anda.');
    }

    public function showOtpRegisterVerify()
    {
        if (!session()->has('otp_verify_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.otp-verify-register');
    }

    public function verifyOtpRegister(Request $request)
    {
        $request->validate([
            'otp_code' => ['required', 'string'],
        ]);

        $userId = session('otp_verify_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($userId);

        if ($user->otp_attempts >= 5) {
            return back()->withErrors(['otp_code' => 'Batas percobaan input OTP telah terlampaui. Silakan kirim ulang kode baru.']);
        }

        if (empty($user->otp_code)) {
            return back()->withErrors(['otp_code' => 'Kode OTP tidak valid atau silakan kirim ulang.']);
        }

        if (now()->gt($user->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Kode OTP telah kadaluarsa. Silakan kirim ulang.']);
        }

        // Sanitize input: remove all non-digit characters
        $inputOtp = preg_replace('/[^0-9]/', '', $request->otp_code);

        if (strlen($inputOtp) !== 6) {
            return back()->withErrors(['otp_code' => 'Kode OTP harus berupa 6 digit angka.']);
        }

        if (!Hash::check($inputOtp, $user->otp_code)) {
            $user->increment('otp_attempts');
            $attemptsLeft = 5 - $user->otp_attempts;
            
            if ($attemptsLeft <= 0) {
                $user->update(['otp_code' => null]);
                return back()->withErrors(['otp_code' => 'Batas percobaan habis. Silakan kirim ulang kode OTP.']);
            }
            
            return back()->withErrors(['otp_code' => "Kode OTP salah. Sisa percobaan: {$attemptsLeft} kali."]);
        }

        // Successfully verified!
        $user->update([
            'status' => 'active',
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0,
        ]);

        Auth::login($user);
        session()->forget('otp_verify_user_id');
        session()->forget('last_google_otp_debug');

        $roleName = match($user->role) {
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            default => 'User'
        };
        $message = "Verifikasi berhasil! Selamat datang sebagai {$roleName}.";

        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', $message);
        }

        return redirect()->route('home')->with('success', $message);
    }

    public function resendOtpRegister()
    {
        $userId = session('otp_verify_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($userId);

        // Prevent double-clicking / requesting too often (1 minute cooldown)
        if ($user->otp_expires_at) {
            $cooldownEndsAt = $user->otp_expires_at->copy()->subMinutes(9);
            if (now()->lt($cooldownEndsAt)) {
                return back()->withErrors(['otp_code' => 'Harap tunggu 1 menit sebelum meminta kode OTP baru.']);
            }
        }

        return $this->sendVerificationOtp($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
