<?php
require_once(__DIR__ . "/../data/db.php");
session_start();
session_regenerate_id();

$schoolID = $_POST['schoolID'] ?? null;

if (!$schoolID) {
    $_SESSION['messages']['deleteError'] = "No school ID provided";
    header("Location: index.php?section=school&page=schoolList", true, 302);
    exit;
}

if ($_POST && isset($_POST['confirmFinalDelete'])) {
    try {
        // Check if there are dependent departments
        $checkStmt = $db->prepare("SELECT COUNT(*) as dept_count FROM departments WHERE deptcollid = ?");
        $checkStmt->execute([$schoolID]);
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && $result['dept_count'] > 0) {
            $_SESSION['messages']['deleteError'] = "Cannot delete school: There are " . $result['dept_count'] . " department(s) associated with this school. Please delete all departments first.";
            header("Location: index.php?section=school&page=schoolDelete&collid=" . urlencode($schoolID), true, 302);
            exit;
        }

        // Delete the school
        $stmt = $db->prepare("DELETE FROM colleges WHERE collid = ?");
        $deleteResult = $stmt->execute([$schoolID]);

        if ($deleteResult && $stmt->rowCount() > 0) {
            $_SESSION['messages']['deleteSuccess'] = "School deleted successfully";
            header("Location: index.php?section=school&page=schoolList", true, 302);
            exit;
        } else {
            $_SESSION['messages']['deleteError'] = "Failed to delete school or school not found";
            header("Location: index.php?section=school&page=schoolDelete&collid=" . urlencode($schoolID), true, 302);
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['messages']['deleteError'] = "Database error: " . $e->getMessage();
        header("Location: index.php?section=school&page=schoolDelete&collid=" . urlencode($schoolID), true, 302);
        exit;
    }
}

// If no final confirmation was submitted, redirect back
$_SESSION['messages']['deleteError'] = "Delete confirmation not received";
header("Location: index.php?section=school&page=schoolList", true, 302);
exit;

?>
