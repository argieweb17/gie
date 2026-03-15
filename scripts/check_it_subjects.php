<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// Check subjects linked to dept 1 (IT)
echo "=== Subjects with department_id=1 (IT) ===\n";
$r = $pdo->query("SELECT COUNT(*) as cnt FROM subject WHERE department_id = 1");
echo "Count: " . $r->fetchColumn() . "\n\n";

// Check curriculum_subject links for curriculum 7 (BSIT)
echo "=== Curriculum-Subject links for BSIT (id=7) ===\n";
$r = $pdo->query("SELECT COUNT(*) as cnt FROM curriculum_subject WHERE curriculum_id = 7");
echo "Count: " . $r->fetchColumn() . "\n\n";

// Check subjects linked via curriculum_subject for BSIT
echo "=== Subjects in BSIT curriculum ===\n";
$r = $pdo->query("SELECT s.id, s.subject_code, s.subject_name, s.department_id FROM subject s INNER JOIN curriculum_subject cs ON s.id = cs.subject_id WHERE cs.curriculum_id = 7 LIMIT 5");
foreach ($r as $row) {
    echo "  ID={$row['id']} | {$row['subject_code']} | {$row['subject_name']} | dept={$row['department_id']}\n";
}

// Check what dept.subjects means - it's likely a OneToMany on department_id
echo "\n=== All subjects with department_id=1 ===\n";
$r = $pdo->query("SELECT id, subject_code, subject_name FROM subject WHERE department_id = 1 LIMIT 5");
$count = 0;
foreach ($r as $row) {
    echo "  {$row['id']} | {$row['subject_code']} | {$row['subject_name']}\n";
    $count++;
}
if ($count == 0) echo "  (none)\n";
