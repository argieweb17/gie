<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
echo "=== Curricula ===\n";
foreach ($pdo->query('SELECT id, curriculum_name, department_id FROM curriculum ORDER BY id') as $r) {
    echo $r['id'] . ' | ' . $r['curriculum_name'] . ' | dept=' . $r['department_id'] . "\n";
}
echo "\n=== Departments ===\n";
foreach ($pdo->query('SELECT id, department_name FROM department ORDER BY id') as $r) {
    echo $r['id'] . ' | ' . $r['department_name'] . "\n";
}
