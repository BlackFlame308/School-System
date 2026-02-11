<?php
require_once(__DIR__ . "/../data/db.php");
session_start();
session_regenerate_id();

$progid = $_GET['progid'] ?? null;
if (!$progid) {
    header("Location: index.php?section=programs&page=choosePrograms", true, 301);
    exit;
}

$entryURL = "index.php?section=students&page=studentCreate&progid=" . $progid;

if ($_POST && isset($_POST['clearEntries'])) {
    $_SESSION['input'] = [];
    $_SESSION['messages'] = [];
    $_SESSION['errors'] = [];
    header("Location: $entryURL", true, 301);
    exit;
}

if ($_POST && isset($_POST['saveNewStudent'])) {
    $studid = trim($_POST['studid'] ?? '');
    $studfirstname = trim($_POST['studfirstname'] ?? '');
    $studlastname = trim($_POST['studlastname'] ?? '');

    $_SESSION['input']['studid'] = $studid;
    $_SESSION['input']['studfirstname'] = $studfirstname;
    $_SESSION['input']['studlastname'] = $studlastname;

    if (!isset($_SESSION['errors'])) $_SESSION['errors'] = [];

    $isValid = true;

    if (empty($studid) || !preg_match('/^\d+$/', $studid)) {
        $_SESSION['errors']['studid'] = "Student ID is required and must be numeric";
        $isValid = false;
    } else {
        try {
            $check = $db->prepare("SELECT COUNT(*) FROM students WHERE studid = ?");
            $check->execute([$studid]);
            if ($check->fetchColumn() > 0) {
                $_SESSION['errors']['studid'] = "Student ID already exists";
                $isValid = false;
            } else {
                $_SESSION['errors']['studid'] = "";
            }
        } catch (PDOException $e) {
            $_SESSION['errors']['studid'] = "Error checking student ID";
            $isValid = false;
        }
    }

    if (empty($studfirstname)) {
        $_SESSION['errors']['studfirstname'] = "First name is required";
        $isValid = false;
    }

    if (empty($studlastname)) {
        $_SESSION['errors']['studlastname'] = "Last name is required";
        $isValid = false;
    }

    if ($isValid) {
        try {
            // Fetch program's department and college ids (some schemas require studcollid, studcolldeptid)
            $deptid = null;
            $collid = null;
            try {
                $pstmt = $db->prepare("SELECT progcolldeptid, progcollid FROM programs WHERE progid = ?");
                $pstmt->execute([$progid]);
                $prog = $pstmt->fetch();
                $deptid = $prog['progcolldeptid'] ?? null;
                $collid = $prog['progcollid'] ?? null;
            } catch (PDOException $e) {
                // ignore, will use minimal insert
            }

            // Try to insert with all available fields
            if ($collid !== null && $deptid !== null) {
                $stmt = $db->prepare("INSERT INTO students (studid, studfirstname, studlastname, studprogid, studcolldeptid, studcollid, studyear) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $res = $stmt->execute([$studid, $studfirstname, $studlastname, $progid, $deptid, $collid, 1]);
            } elseif ($collid !== null) {
                $stmt = $db->prepare("INSERT INTO students (studid, studfirstname, studlastname, studprogid, studcollid, studyear) VALUES (?, ?, ?, ?, ?, ?)");
                $res = $stmt->execute([$studid, $studfirstname, $studlastname, $progid, $collid, 1]);
            } elseif ($deptid !== null) {
                $stmt = $db->prepare("INSERT INTO students (studid, studfirstname, studlastname, studprogid, studcolldeptid, studyear) VALUES (?, ?, ?, ?, ?, ?)");
                $res = $stmt->execute([$studid, $studfirstname, $studlastname, $progid, $deptid, 1]);
            } else {
                $stmt = $db->prepare("INSERT INTO students (studid, studfirstname, studlastname, studprogid, studyear) VALUES (?, ?, ?, ?, ?)");
                $res = $stmt->execute([$studid, $studfirstname, $studlastname, $progid, 1]);
            }
            if ($res) {
                $_SESSION['messages']['createSuccess'] = "Student created successfully";
                $_SESSION['input'] = [];
                $_SESSION['errors'] = [];
            } else {
                $_SESSION['messages']['createError'] = "Failed to create student";
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
