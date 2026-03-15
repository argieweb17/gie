<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$mapping = [
    '1' => 'First Year',
    '2' => 'Second Year',
    '3' => 'Third Year',
    '4' => 'Fourth Year',
];

foreach ($mapping as $old => $new) {
    $stmt = $pdo->prepare("UPDATE subject SET year_level = :new WHERE year_level = :old");
    $stmt->execute(['new' => $new, 'old' => $old]);
    echo "Updated year_level '$old' → '$new' ({$stmt->rowCount()} rows)\n";
}

echo "\nDistinct year_level values now:\n";
$r = $pdo->query('SELECT DISTINCT year_level FROM subject ORDER BY year_level');
while ($row = $r->fetch(PDO::FETCH_NUM)) {
    echo "  " . $row[0] . "\n";
}
