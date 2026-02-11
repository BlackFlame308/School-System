<?php
require_once(__DIR__ . "/../data/db.php");

session_start();
session_regenerate_id();

$deptid = $_GET['deptid'] ?? null;

if (!$deptid) {
    header("Location: index.php?section=department&page=chooseSchool", true, 301);
    exit;
}

// Get department info for redirect
$dbStatement = $db->prepare("SELECT deptcollid FROM departments WHERE deptid = :deptid");
$dbStatement->execute(['deptid' => $deptid]);
$dept = $dbStatement->fetch();

if (!$dept) {
    header("Location: index.php?section=CORE_PHP&page=404", true, 301);
    exit;
}

$collid = $dept['deptcollid'];

if($_POST && isset($_POST['confirmDelete'])){
    try {
        // Check for dependent programs
        $checkStmt = $db->prepare("SELECT COUNT(*) as prog_count FROM programs WHERE progcolldeptid = ?");
        $checkStmt->execute([$deptid]);
        $result = $checkStmt->fetch();
        
        if($result['prog_count'] > 0) {
            $_SESSION['messages']['deleteError'] = "Cannot delete department because it has programs. Delete all programs first.";
            $_SESSION['messages']['deleteSuccess'] = "";
            header("Location: index.php?section=department&page=departmentDelete&deptid=" . $deptid, true, 301);
            exit;
        }
        
        $dbStatement = $db->prepare('DELETE FROM departments WHERE deptid = ?');
        $dbResult = $dbStatement->execute([$deptid]);

        if($dbResult && $dbStatement->rowCount() > 0){
            $_SESSION['messages']['deleteSuccess'] = "Department deleted successfully";
            $_SESSION['messages']['deleteError'] = "";
            
            // Redirect to department list
            header("Location: index.php?section=department&page=departmentList&deptcollid=" . $collid, true, 301);
            exit;
        } else {
            $_SESSION['messages']['deleteError'] = "Failed to delete department or department not found";
            $_SESSION['messages']['deleteSuccess'] = "";
            
            header("Location: index.php?section=department&page=departmentDelete&deptid=" . $deptid, true, 301);
            exit;
        }
    } catch(PDOException $e) {
        $_SESSION['messages']['deleteError'] = "Database error: " . $e->getMessage();
        $_SESSION['messages']['deleteSuccess'] = "";
        
        header("Location: index.php?section=department&page=departmentDelete&deptid=" . $deptid, true, 301);
        exit;
    }
} else {
    header("Location: index.php?section=department&page=departmentDelete&deptid=" . $deptid, true, 301);
    exit;
}
?>





