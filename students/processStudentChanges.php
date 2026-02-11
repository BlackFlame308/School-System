<?php
require_once(__DIR__ . "/../data/db.php");
session_start();
session_regenerate_id();

$progid = $_GET['progid'] ?? null;
$studid = $_GET['studid'] ?? null;
if (!$progid || !$studid) {
    header("Location: index.php?section=programs&page=choosePrograms", true, 301);
    exit;
}

$entryURL = "index.php?section=students&page=studentUpdate&progid=" . $progid . "&studid=" . $studid;

if ($_POST && isset($_POST['saveStudentChanges'])) {
    $studfirstname = trim($_POST['studfirstname'] ?? '');
    $studlastname = trim($_POST['studlastname'] ?? '');

    $_SESSION['input']['studfirstname'] = $studfirstname;
    $_SESSION['input']['studlastname'] = $studlastname;

    if (!isset($_SESSION['errors'])) $_SESSION['errors'] = [];

    $isValid = true;
    if (empty($studfirstname)) {
        $_SESSION['errors']['studfirstname'] = "First name is required";
        $isValid = false;
    } else {
        $_SESSION['errors']['studfirstname'] = "";
    }

    if (empty($studlastname)) {
        $_SESSION['errors']['studlastname'] = "Last name is required";
        $isValid = false;
    } else {
        $_SESSION['errors']['studlastname'] = "";
    }

    if ($isValid) {
        try {
            $stmt = $db->prepare("UPDATE students SET studfirstname = ?, studlastname = ? WHERE studid = ?");
            $res = $stmt->execute([$studfirstname, $studlastname, $studid]);
            if ($res) {
                $_SESSION['messages']['updateSuccess'] = "Student updated successfully";
                $_SESSION['input'] = [];
                $_SESSION['errors'] = [];
            } else {
                $_SESSION['messages']['updateError'] = "Failed to update student";
            }
        } catch (PDOException $e) {
            $_SESSION['messages']['updateError'] = "Database error: " . $e->getMessage();
        }
    } else {
        $_SESSION['messages']['updateError'] = "Please correct the errors above";
    }

    header("Location: $entryURL", true, 301);
    exit;
}

?>
