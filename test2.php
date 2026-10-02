<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'test' . time() . '@example.com';
$user = App\Models\User::create([
    'name' => 'test',
    'email' => $email,
    'password' => Illuminate\Support\Facades\Hash::make('password'),
    'role' => 'user',
    'status' => 'pending'
]);

$otpCode = sprintf('%06d', rand(100000, 999999));

$user->update([
    'otp_code' => Illuminate\Support\Facades\Hash::make($otpCode),
    'otp_expires_at' => now()->addMinutes(10),
    'otp_attempts' => 0,
]);

// Fetch from DB to ensure it was saved correctly
$userFromDb = App\Models\User::find($user->id);

$check = Illuminate\Support\Facades\Hash::check($otpCode, $userFromDb->otp_code);
echo "Hash Check Result for new user ($email): " . ($check ? 'true' : 'false') . "\n";
