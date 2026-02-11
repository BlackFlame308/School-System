<?php
require_once(__DIR__ . "/../data/db.php");
session_start();
session_regenerate_id();

$deptid = $_GET['deptid'] ?? null;
if (!$deptid) {
    header("Location: index.php?section=programs&page=choosePrograms", true, 301);
    exit;
}

$entryURL = "index.php?section=programs&page=programsCreate&deptid=" . $deptid;

if ($_POST && isset($_POST['clearEntries'])) {
    $_SESSION['input']['progid'] = null;
    $_SESSION['input']['progfullname'] = null;
    $_SESSION['input']['progshortname'] = null;
    $_SESSION['messages']['createSuccess'] = "";
    $_SESSION['messages']['createError'] = "";
    $_SESSION['errors'] = [];

    header("Location: $entryURL", true, 301);
    exit;
}

if ($_POST && isset($_POST['saveNewProgram'])) {
    $progid = trim($_POST['progid'] ?? '');
    $progfullname = trim($_POST['progfullname'] ?? '');
    $progshortname = trim($_POST['progshortname'] ?? '');

    $_SESSION['input']['progid'] = $progid;
    $_SESSION['input']['progfullname'] = $progfullname;
    $_SESSION['input']['progshortname'] = $progshortname;

    if (!isset($_SESSION['errors'])) $_SESSION['errors'] = [];

    $isValid = true;

    if (empty($progid) || !preg_match('/^\d+$/', $progid)) {
        $_SESSION['errors']['progid'] = "Program ID is required and must be numeric";
        $isValid = false;
    } else {
        try {
            $check = $db->prepare("SELECT COUNT(*) FROM programs WHERE progid = ?");
            $check->execute([$progid]);
            if ($check->fetchColumn() > 0) {
                $_SESSION['errors']['progid'] = "Program ID already exists";
                $isValid = false;
            } else {
                $_SESSION['errors']['progid'] = "";
            }
        } catch (PDOException $e) {
            $_SESSION['errors']['progid'] = "Error checking program ID";
            $isValid = false;
        }
    }

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
            // fetch the college id for this department (some schemas require progcollid)
            $collId = null;
            try {
                $cstmt = $db->prepare("SELECT deptcollid FROM departments WHERE deptid = ?");
                $cstmt->execute([$deptid]);
                $crow = $cstmt->fetch();
                $collId = $crow['deptcollid'] ?? null;
            } catch (PDOException $e) {
                // ignore, collId will be null
            }

            if ($collId !== null) {
                $stmt = $db->prepare("INSERT INTO programs (progid, progfullname, progshortname, progcolldeptid, progcollid) VALUES (?, ?, ?, ?, ?)");
                $res = $stmt->execute([$progid, $progfullname, $progshortname, $deptid, $collId]);
            } else {
                $stmt = $db->prepare("INSERT INTO programs (progid, progfullname, progshortname, progcolldeptid) VALUES (?, ?, ?, ?)");
                $res = $stmt->execute([$progid, $progfullname, $progshortname, $deptid]);
            }
            if ($res) {
                $_SESSION['messages']['createSuccess'] = "Program created successfully";
                $_SESSION['messages']['createError'] = "";
                $_SESSION['input'] = [];
                $_SESSION['errors'] = [];
            } else {
                $_SESSION['messages']['createError'] = "Failed to create program";
            }
        } catch (PDOException $e) {
            $_SESSION['messages']['createError'] = "Database error: " . $e->getMessage();
        }
    } else {
        $_SESSION['messages']['createError'] = "Please correct the errors above";
    }

    header("Location: $entryURL", true, 301);
    exit;
}

?>
