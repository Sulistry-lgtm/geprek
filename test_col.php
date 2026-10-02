<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first();
echo "Column Type: " . Illuminate\Support\Facades\Schema::getColumnType('users', 'otp_code') . "\n";

$column = Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM users WHERE Field = 'otp_code'");
var_dump($column);
