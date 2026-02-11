<?php

$dbInfo = parse_ini_file("db.ini");

$host = $dbInfo["host"];
$user = $dbInfo["user"];
$password = $dbInfo["password"];
$dbname = $dbInfo["dbname"];
$port = $dbInfo["port"];

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
} catch(PDOException $e) {
    // echo "Connection failed: " . $e->getMessage();
    http_response_code(500);
    header("Cache-Control: no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Location: index.php?section=500&page=500", false, 302);
}