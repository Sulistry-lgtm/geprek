<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $otpCode = "123456";
    Illuminate\Support\Facades\Mail::to('geprekbestie76@gmail.com')->send(new App\Mail\GoogleOtpMail($otpCode));
    echo "GoogleOtpMail sent successfully to geprekbestie76@gmail.com.\n";
} catch (\Throwable $e) {
    echo "Mail sending failed.\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
