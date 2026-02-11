<?php
require_once(__DIR__ . "/../data/db.php");
session_start();
session_regenerate_id();

$deptid = $_GET['deptid'] ?? null;
$progid = $_GET['progid'] ?? null;
if (!$deptid || !$progid) {
    header("Location: index.php?section=programs&page=choosePrograms", true, 301);
    exit;
}

$entryURL = "index.php?section=programs&page=programsUpdate&deptid=" . $deptid . "&progid=" . $progid;

if ($_POST && isset($_POST['saveProgramChanges'])) {
    $progfullname = trim($_POST['progfullname'] ?? '');
    $progshortname = trim($_POST['progshortname'] ?? '');

    $_SESSION['input']['progfullname'] = $progfullname;
    $_SESSION['input']['progshortname'] = $progshortname;

    if (!isset($_SESSION['errors'])) $_SESSION['errors'] = [];

    $isValid = true;
    if (empty($progfullname)) {
        $_SESSION['errors']['progfullname'] = "Program full name is required";
        $isValid = false;
    } else {
        $_SESSION['errors']['progfullname'] = "";
    }

    if (empty($progshortname)) {
        $_SESSION['errors']['progshortname'] = "Program short name is required";
        $isValid = false;
    } else {
        $_SESSION['errors']['progshortname'] = "";
    }

    if ($isValid) {
        try {
            $stmt = $db->prepare("UPDATE programs SET progfullname = ?, progshortname = ? WHERE progid = ?");
            $res = $stmt->execute([$progfullname, $progshortname, $progid]);
            if ($res) {
                $_SESSION['messages']['updateSuccess'] = "Program updated successfully";
                $_SESSION['messages']['updateError'] = "";
                $_SESSION['input'] = [];
                $_SESSION['errors'] = [];
            } else {
                $_SESSION['messages']['updateError'] = "Failed to update program";
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
