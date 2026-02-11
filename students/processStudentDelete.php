<?php
require_once(__DIR__ . "/../data/db.php");
session_start();
session_regenerate_id();

$studid = $_GET['studid'] ?? null;
$progid = $_GET['progid'] ?? null;
if (!$studid) {
    header("Location: index.php?section=programs&page=choosePrograms", true, 301);
    exit;
}

try {
    $del = $db->prepare("DELETE FROM students WHERE studid = ?");
    $del->execute([$studid]);

    header("Location: index.php?section=students&page=studentsList&progid=" . ($progid ?? ''), true, 301);
    exit;
} catch (PDOException $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}

?>
