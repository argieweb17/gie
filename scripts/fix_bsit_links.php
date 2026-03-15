<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// Get all IT department subjects
$ids = $pdo->query("SELECT id FROM subject WHERE department_id = 1 ORDER BY id")->fetchAll(PDO::FETCH_COLUMN);
echo count($ids) . " IT subjects found\n";

// Re-link to BSIT curriculum (id=7)
$stmt = $pdo->prepare("INSERT IGNORE INTO curriculum_subject (curriculum_id, subject_id) VALUES (7, ?)");
$linked = 0;
foreach ($ids as $id) {
    $stmt->execute([$id]);
    $linked++;
}
echo "$linked subjects linked to BSIT Curriculum (id=7)\n";

// Verify
$count = $pdo->query("SELECT COUNT(*) FROM curriculum_subject WHERE curriculum_id = 7")->fetchColumn();
echo "Verification: $count links in curriculum_subject for BSIT\n";
