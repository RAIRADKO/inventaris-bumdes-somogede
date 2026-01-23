<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $seeder = new \Database\Seeders\DatabaseSeeder();
    $seeder->run();
    echo "Seeding completed successfully!\n";
} catch (Exception $e) {
    echo "=== ERROR DETAILS ===\n";
    echo "Message: " . $e->getMessage() . "\n\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    
    if (method_exists($e, 'getSql')) {
        echo "SQL: " . $e->getSql() . "\n\n";
    }
    
    echo "=== STACK TRACE ===\n";
    $trace = $e->getTrace();
    foreach (array_slice($trace, 0, 10) as $i => $t) {
        $file = isset($t['file']) ? $t['file'] : 'unknown';
        $line = isset($t['line']) ? $t['line'] : 0;
        $func = isset($t['function']) ? $t['function'] : 'unknown';
        $class = isset($t['class']) ? $t['class'] . '::' : '';
        echo "#$i $file:$line $class$func()\n";
    }
}
