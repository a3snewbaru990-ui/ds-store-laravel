<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo 'KEY=', config('app.key'), PHP_EOL;
echo 'ENV=', config('app.env'), PHP_EOL;
