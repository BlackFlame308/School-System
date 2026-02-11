<?php
require_once(__DIR__ . "/../data/db.php");
session_start();
session_regenerate_id();

$progid = $_GET['progid'] ?? null;
if (!$progid) {
    header("Location: index.php?section=programs&page=choosePrograms", true, 301);
    exit;
}

try {
    $stmt = $db->prepare("SELECT progcolldeptid FROM programs WHERE progid = ?");
    $stmt->execute([$progid]);
    $row = $stmt->fetch();
    $deptid = $row['progcolldeptid'] ?? null;

    $del = $db->prepare("DELETE FROM programs WHERE progid = ?");
    $del->execute([$progid]);

    header("Location: index.php?section=programs&page=programsList&deptid=" . ($deptid ?? ''), true, 301);
    exit;
} catch (PDOException $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}

?>
