<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Hash;
use App\Models\User;

$user = User::latest('id')->first();

$otpCode = sprintf('%06d', rand(100000, 999999));
echo "Generated OTP: $otpCode\n";

$user->update([
    'otp_code' => Hash::make($otpCode)
]);

$user->refresh();

$isMatch = Hash::check($otpCode, $user->otp_code);
echo "Hash in DB: {$user->otp_code}\n";
echo "Match exact: " . ($isMatch ? 'YES' : 'NO') . "\n";
echo "Match with space: " . (Hash::check($otpCode . ' ', $user->otp_code) ? 'YES' : 'NO') . "\n";
