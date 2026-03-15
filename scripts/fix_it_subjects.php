<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// Fix BSIT subjects: set department_id=1 (IT) for all subjects in curriculum 7
$stmt = $pdo->prepare("
    UPDATE subject s 
    INNER JOIN curriculum_subject cs ON s.id = cs.subject_id 
    SET s.department_id = 1 
    WHERE cs.curriculum_id = 7 AND (s.department_id IS NULL OR s.department_id = 0)
");
$stmt->execute();
echo "BSIT subjects updated: " . $stmt->rowCount() . " rows\n";

// Verify
$count = $pdo->query("SELECT COUNT(*) FROM subject WHERE department_id = 1")->fetchColumn();
echo "Total IT subjects now: $count\n";
