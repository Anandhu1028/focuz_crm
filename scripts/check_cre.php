<?php
// Simple script to inspect payments.customer_relation_executive
// Usage: php scripts/check_cre.php [student_id]

// Robust .env parsing (handle quotes, empty values)
$envPath = __DIR__ . '/../.env';
$env = [];
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        // remove surrounding quotes if present
        $value = preg_replace('/^\"|\"$|^\'|\'$/', '', $value);
        $env[$key] = $value;
    }
}

$db = $env['DB_DATABASE'] ?? getenv('DB_DATABASE');
$host = $env['DB_HOST'] ?? getenv('DB_HOST');
$port = $env['DB_PORT'] ?? getenv('DB_PORT') ?? 3306;
$user = $env['DB_USERNAME'] ?? getenv('DB_USERNAME');
$pass = $env['DB_PASSWORD'] ?? getenv('DB_PASSWORD');

$studentId = isset($argv[1]) ? $argv[1] : null;

$dsn = "mysql:host={$host};dbname={$db};port={$port};charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    echo "DB connection failed: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

if ($studentId) {
    $stmt = $pdo->prepare("SELECT id, student_id, customer_relation_executive, amount, payment_date FROM payments WHERE student_id = :sid ORDER BY id DESC LIMIT 50");
    $stmt->execute([':sid' => $studentId]);
} else {
    $stmt = $pdo->query("SELECT id, student_id, customer_relation_executive, amount, payment_date FROM payments ORDER BY id DESC LIMIT 50");
}
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$rows) {
    echo "No payments found." . PHP_EOL;
    exit(0);
}

echo str_pad('id', 6) . str_pad('student_id', 12) . str_pad('cre', 12) . str_pad('amount', 12) . 'payment_date' . PHP_EOL;
foreach ($rows as $r) {
    $cre = $r['customer_relation_executive'] === null ? 'NULL' : $r['customer_relation_executive'];
    echo str_pad($r['id'], 6) . str_pad($r['student_id'], 12) . str_pad($cre, 12) . str_pad($r['amount'], 12) . $r['payment_date'] . PHP_EOL;
}

return 0;
