<?php
require_once(__DIR__ . "/../data/db.php");

session_start();
session_regenerate_id();

$deptid = $_GET['deptid'] ?? null;

if (!$deptid) {
    header("Location: index.php?section=department&page=chooseSchool", true, 301);
    exit;
}

// Get department info
$dbStatement = $db->prepare("SELECT deptcollid FROM departments WHERE deptid = :deptid");
$dbStatement->execute(['deptid' => $deptid]);
$dept = $dbStatement->fetch();

if (!$dept) {
    header("Location: index.php?section=CORE_PHP&page=404", true, 301);
    exit;
}

$collid = $dept['deptcollid'];
$entryURL = "index.php?section=department&page=departmentUpdate&deptid=" . $deptid;

if($_POST && isset($_POST['clearChanges'])){
    $_SESSION['errors']['deptFullName'] = "";
    $_SESSION['errors']['deptShortName'] = "";
    $_SESSION['messages']['updateSuccess'] = "";
    $_SESSION['messages']['updateError'] = "";
    $_SESSION['input']['deptFullName'] = "";
    $_SESSION['input']['deptShortName'] = "";

    header("Location: $entryURL", true, 301);
    exit;
}

if($_POST && isset($_POST['saveChanges'])){
    $deptID = $_POST['deptID'];
    $deptFullName = trim($_POST['deptFullName'] ?? '');
    $deptShortName = trim($_POST['deptShortName'] ?? '');

    $_SESSION['input']['deptFullName'] = $deptFullName;
    $_SESSION['input']['deptShortName'] = $deptShortName;

    if(!isset($_SESSION['errors'])) $_SESSION['errors'] = [];
    if(!isset($_SESSION['messages'])) $_SESSION['messages'] = [];

    // Validation
    $isValid = true;
    
    if(empty($deptFullName) || !preg_match('/^[A-Za-z\s\-\'\.&]+$/', $deptFullName)){
        $_SESSION['errors']['deptFullName'] = "Invalid full name";
        $isValid = false;
    } else {
        $_SESSION['errors']['deptFullName'] = "";
    }

    if(empty($deptShortName) || !preg_match('/^[A-Za-z\s\-\'\.&]+$/', $deptShortName)){
        $_SESSION['errors']['deptShortName'] = "Invalid short name";
        $isValid = false;
    } else {
        $_SESSION['errors']['deptShortName'] = "";
    }

    if($isValid){
        try {
            $dbStatement = $db->prepare('UPDATE departments SET deptfullname = ?, deptshortname = ? WHERE deptid = ?');
            $dbResult = $dbStatement->execute([$deptFullName, $deptShortName, $deptID]);

            if($dbResult && $dbStatement->rowCount() > 0){
                $_SESSION['messages']['updateSuccess'] = "Department updated successfully";
                $_SESSION['messages']['updateError'] = "";
                
                // Clear input values
                $_SESSION['input']['deptFullName'] = "";
                $_SESSION['input']['deptShortName'] = "";
            } else {
                $_SESSION['messages']['updateError'] = "No changes made or department not found";
                $_SESSION['messages']['updateSuccess'] = "";
            }
        } catch(PDOException $e) {
            $_SESSION['messages']['updateError'] = "Database error: " . $e->getMessage();
            $_SESSION['messages']['updateSuccess'] = "";
        }
    } else {
        $_SESSION['messages']['updateError'] = "Please correct the errors above";
        $_SESSION['messages']['updateSuccess'] = "";
    }

    header("Location: $entryURL", true, 301);
    exit;
}
?>





