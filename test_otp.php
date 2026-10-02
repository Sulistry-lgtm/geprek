<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::latest('id')->first();
echo "ID: {$user->id}\n";
echo "Email: {$user->email}\n";
echo "Status: {$user->status}\n";
echo "OTP Code: {$user->otp_code}\n";
echo "Attempts: {$user->otp_attempts}\n";
