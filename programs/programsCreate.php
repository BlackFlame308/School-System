<?php
session_start();
session_regenerate_id();

$deptid = $_GET['deptid'] ?? null;
if (!$deptid) {
    header("Location: index.php?section=programs&page=choosePrograms", true, 301);
    exit;
}

require_once(__DIR__ . "/../data/db.php");

$dbStatement = $db->prepare("SELECT * FROM departments WHERE deptid = :deptid");
$dbStatement->execute(['deptid' => $deptid]);
$department = $dbStatement->fetch();
?>

<h1>Create Program - <?php echo htmlspecialchars($department['deptfullname']); ?></h1>
<div class="message"><?php echo $_SESSION['messages']['createSuccess'] ?? $_SESSION['messages']['createError'] ?? ''; ?></div>
<form action="index.php?section=programs&page=processProgramsData&deptid=<?php echo $deptid; ?>" method="post">
    <table>
        <tr>
            <td style="width: 10em;">Program ID:</td>
            <td style="width: 30em;"><input type="text" name="progid" value="<?= htmlspecialchars($_SESSION['input']['progid'] ?? '') ?>" required pattern="[0-9]+"></td>
            <td><div class="error"><?php echo $_SESSION['errors']['progid'] ?? ''; ?></div></td>
        </tr>
        <tr>
            <td>Program Full Name:</td>
            <td><input type="text" name="progfullname" value="<?= htmlspecialchars($_SESSION['input']['progfullname'] ?? '') ?>" required></td>
            <td><div class="error"><?php echo $_SESSION['errors']['progfullname'] ?? ''; ?></div></td>
        </tr>
        <tr>
            <td>Program Short Name:</td>
            <td><input type="text" name="progshortname" value="<?= htmlspecialchars($_SESSION['input']['progshortname'] ?? '') ?>" required></td>
            <td><div class="error"><?php echo $_SESSION['errors']['progshortname'] ?? ''; ?></div></td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit" name="saveNewProgram" class="btn btn-primary">Save New Program</button>
                <button type="submit" name="clearEntries" class="btn">Reset Form</button>
                <a href="index.php?section=programs&page=programsList&deptid=<?php echo $deptid; ?>" class="btn btn-danger">Exit</a>
            </td>
        </tr>
    </table>
</form>
