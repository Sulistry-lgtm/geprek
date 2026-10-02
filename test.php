<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first();
if (!$user) {
    $user = App\Models\User::create([
        'name' => 'test',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
        'role' => 'user',
        'status' => 'pending'
    ]);
}

$otpCode = sprintf('%06d', rand(100000, 999999));
echo "Generated OTP: " . $otpCode . "\n";

$user->update([
    'otp_code' => Hash::make($otpCode),
    'otp_expires_at' => now()->addMinutes(10),
    'otp_attempts' => 0,
]);

$user->refresh();

$check = Hash::check($otpCode, $user->otp_code);
echo "Hash Check Result: " . ($check ? 'true' : 'false') . "\n";
