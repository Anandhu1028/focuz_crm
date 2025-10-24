<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$p = App\Models\Payments::where('student_id', 532)->orderBy('id', 'desc')->first();
if ($p) {
    echo "payment id:" . $p->id . "\nCRE:" . ($p->customer_relation_executive ?: 'NULL') . "\n";
} else {
    echo "NO PAYMENT\n";
}
