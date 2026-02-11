<?php
require_once(__DIR__ . "/../data/db.php");

session_start();
session_regenerate_id();

$collid = $_GET['deptcollid'] ?? null;

if (!$collid) {
    header("Location: index.php?section=department&page=chooseSchool", true, 301);
    exit;
}

$entryURL = "index.php?section=department&page=departmentCreate&deptcollid=" . $collid;

if($_POST && isset($_POST['clearEntries'])){
    $_SESSION['input']['deptID'] = null;
    $_SESSION['input']['deptFullName'] = null;
    $_SESSION['input']['deptShortName'] = null;
    $_SESSION['messages']['createSuccess'] = "";
    $_SESSION['messages']['createError'] = "";    
    $_SESSION['errors']['deptID'] = "";
    $_SESSION['errors']['deptFullName'] = "";
    $_SESSION['errors']['deptShortName'] = "";

    header("Location: $entryURL", true, 301);
    exit;
}

if($_POST && isset($_POST['saveNewDepartmentEntry'])){
    $deptID = trim($_POST['deptID'] ?? '');
    $deptFullName = trim($_POST['deptFullName'] ?? '');
    $deptShortName = trim($_POST['deptShortName'] ?? '');

    $_SESSION['input']['deptID'] = $deptID;
    $_SESSION['input']['deptFullName'] = $deptFullName;
    $_SESSION['input']['deptShortName'] = $deptShortName;

    if(!isset($_SESSION['errors'])) $_SESSION['errors'] = [];
    if(!isset($_SESSION['messages'])) $_SESSION['messages'] = [];

    // Validation
    $isValid = true;
    
    // Validate Department ID
    if(empty($deptID)){
        $_SESSION['errors']['deptID'] = "Department ID is required";
        $isValid = false;
    } elseif(!preg_match('/^\d+$/', $deptID)){
        $_SESSION['errors']['deptID'] = "Department ID must be numeric";
        $isValid = false;
    } else {
        // Check if ID already exists
        try {
            $checkStmt = $db->prepare("SELECT COUNT(*) FROM departments WHERE deptid = ?");
            $checkStmt->execute([$deptID]);
            if($checkStmt->fetchColumn() > 0){
                $_SESSION['errors']['deptID'] = "Department ID already exists";
                $isValid = false;
            } else {
                $_SESSION['errors']['deptID'] = "";
            }
        } catch(PDOException $e) {
            $_SESSION['errors']['deptID'] = "Error checking department ID";
            $isValid = false;
        }
    }

    // Validate Full Name
    if(empty($deptFullName)){
        $_SESSION['errors']['deptFullName'] = "Department full name is required";
        $isValid = false;
    } elseif(!preg_match('/^[A-Za-z\s\-\'\.&]+$/', $deptFullName)){
        $_SESSION['errors']['deptFullName'] = "Invalid characters in full name";
        $isValid = false;
    } else {
        $_SESSION['errors']['deptFullName'] = "";
    }

    // Validate Short Name
    if(empty($deptShortName)){
        $_SESSION['errors']['deptShortName'] = "Department short name is required";
        $isValid = false;
    } elseif(!preg_match('/^[A-Za-z\s\-\'\.&]+$/', $deptShortName)){
        $_SESSION['errors']['deptShortName'] = "Invalid characters in short name";
        $isValid = false;
    } else {
        $_SESSION['errors']['deptShortName'] = "";
    }

    if($isValid){
        try {
            $dbStatement = $db->prepare("INSERT INTO departments (deptid, deptfullname, deptshortname, deptcollid) VALUES (?, ?, ?, ?)");
            $dbResult = $dbStatement->execute([$deptID, $deptFullName, $deptShortName, $collid]);

            if($dbResult){
                $_SESSION['messages']['createSuccess'] = "Department created successfully";
                $_SESSION['messages']['createError'] = "";
                
                // Clear input values
                $_SESSION['input']['deptID'] = null;
                $_SESSION['input']['deptFullName'] = null;
                $_SESSION['input']['deptShortName'] = null;
                
                // Clear errors
                $_SESSION['errors']['deptID'] = "";
                $_SESSION['errors']['deptFullName'] = "";
                $_SESSION['errors']['deptShortName'] = "";
            } else {
                $_SESSION['messages']['createError'] = "Failed to create department";
                $_SESSION['messages']['createSuccess'] = "";
            }        
        } catch(PDOException $e) {
            $_SESSION['messages']['createError'] = "Database error: " . $e->getMessage();
            $_SESSION['messages']['createSuccess'] = "";
        }
    } else {
        $_SESSION['messages']['createError'] = "Please correct the errors above";
        $_SESSION['messages']['createSuccess'] = "";
    }

    header("Location: $entryURL", true, 301);
    exit;
}
?>





