<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Illuminate\Support\Facades\Mail::raw('This is a test email to check SMTP settings.', function ($message) {
        $message->to('geprekbestie76@gmail.com')
                ->subject('Test SMTP Connection');
    });
    echo "Mail sent successfully.\n";
} catch (\Exception $e) {
    echo "Mail sending failed.\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
