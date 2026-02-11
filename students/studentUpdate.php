<?php
session_start();
session_regenerate_id();

$studid = $_GET['studid'] ?? null;
$progid = $_GET['progid'] ?? null;
if (!$studid || !$progid) {
    header("Location: index.php?section=programs&page=choosePrograms", true, 301);
    exit;
}

require_once(__DIR__ . "/../data/db.php");

$dbStatement = $db->prepare("SELECT * FROM students WHERE studid = :studid");
$dbStatement->execute(['studid' => $studid]);
$student = $dbStatement->fetch();
?>

<h1>Update Student - <?php echo htmlspecialchars($student['studfirstname'] . ' ' . $student['studlastname']); ?></h1>
<div class="message"><?php echo $_SESSION['messages']['updateSuccess'] ?? $_SESSION['messages']['updateError'] ?? ''; ?></div>
<form action="index.php?section=students&page=processStudentChanges&progid=<?php echo $progid; ?>&studid=<?php echo $studid; ?>" method="post">
    <table>
        <tr>
            <td>Student ID:</td>
            <td><input type="text" name="studid" value="<?= htmlspecialchars($student['studid']) ?>" readonly></td>
        </tr>
        <tr>
            <td>First Name:</td>
            <td><input type="text" name="studfirstname" value="<?= htmlspecialchars($_SESSION['input']['studfirstname'] ?? $student['studfirstname']) ?>" required></td>
            <td><div class="error"><?php echo $_SESSION['errors']['studfirstname'] ?? ''; ?></div></td>
        </tr>
        <tr>
            <td>Last Name:</td>
            <td><input type="text" name="studlastname" value="<?= htmlspecialchars($_SESSION['input']['studlastname'] ?? $student['studlastname']) ?>" required></td>
            <td><div class="error"><?php echo $_SESSION['errors']['studlastname'] ?? ''; ?></div></td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit" name="saveStudentChanges" class="btn btn-primary">Save Changes</button>
                <a href="index.php?section=students&page=studentsList&progid=<?php echo $progid; ?>" class="btn btn-danger">Exit</a>
            </td>
        </tr>
    </table>
</form>
