<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db;charset=utf8mb4', 'root', '');
$codes = ['ITS 403','ITS 404','ITS 405'];
$in = implode(',', array_fill(0, count($codes), '?'));
$stmt = $pdo->prepare("SELECT s.id,s.subject_code,s.subject_name,s.year_level,u.id as faculty_id,u.first_name,u.last_name FROM subject s LEFT JOIN user u ON u.id=s.faculty_id WHERE s.subject_code IN ($in)");
$stmt->execute($codes);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;
}
