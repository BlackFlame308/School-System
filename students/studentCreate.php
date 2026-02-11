<?php
session_start();
session_regenerate_id();

$progid = $_GET['progid'] ?? null;
if (!$progid) {
    header("Location: index.php?section=programs&page=choosePrograms", true, 301);
    exit;
}

require_once(__DIR__ . "/../data/db.php");

$dbStatement = $db->prepare("SELECT * FROM programs WHERE progid = :progid");
$dbStatement->execute(['progid' => $progid]);
$program = $dbStatement->fetch();
?>

<h1>Create Student - <?php echo htmlspecialchars($program['progfullname']); ?></h1>
<div class="message"><?php echo $_SESSION['messages']['createSuccess'] ?? $_SESSION['messages']['createError'] ?? ''; ?></div>
<form action="index.php?section=students&page=processStudentData&progid=<?php echo $progid; ?>" method="post">
    <table>
        <tr>
            <td style="width: 10em;">Student ID:</td>
            <td style="width: 30em;"><input type="text" name="studid" value="<?= htmlspecialchars($_SESSION['input']['studid'] ?? '') ?>" required pattern="[0-9]+"></td>
            <td><div class="error"><?php echo $_SESSION['errors']['studid'] ?? ''; ?></div></td>
        </tr>
        <tr>
            <td>First Name:</td>
            <td><input type="text" name="studfirstname" value="<?= htmlspecialchars($_SESSION['input']['studfirstname'] ?? '') ?>" required></td>
            <td><div class="error"><?php echo $_SESSION['errors']['studfirstname'] ?? ''; ?></div></td>
        </tr>
        <tr>
            <td>Last Name:</td>
            <td><input type="text" name="studlastname" value="<?= htmlspecialchars($_SESSION['input']['studlastname'] ?? '') ?>" required></td>
            <td><div class="error"><?php echo $_SESSION['errors']['studlastname'] ?? ''; ?></div></td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit" name="saveNewStudent" class="btn btn-primary">Save New Student</button>
                <button type="submit" name="clearEntries" class="btn">Reset Form</button>
                <a href="index.php?section=students&page=studentsList&progid=<?php echo $progid; ?>" class="btn btn-danger">Exit</a>
            </td>
        </tr>
    </table>
</form>
