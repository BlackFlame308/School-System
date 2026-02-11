<?php
session_start();
session_regenerate_id();

$progid = $_GET['progid'] ?? null;
$deptid = $_GET['deptid'] ?? null;
if (!$progid || !$deptid) {
    header("Location: index.php?section=programs&page=choosePrograms", true, 301);
    exit;
}

require_once(__DIR__ . "/../data/db.php");

$dbStatement = $db->prepare("SELECT * FROM programs WHERE progid = :progid");
$dbStatement->execute(['progid' => $progid]);
$program = $dbStatement->fetch();
?>

<h1>Update Program - <?php echo htmlspecialchars($program['progfullname']); ?></h1>
<div class="message"><?php echo $_SESSION['messages']['updateSuccess'] ?? $_SESSION['messages']['updateError'] ?? ''; ?></div>
<form action="index.php?section=programs&page=processProgramsChanges&deptid=<?php echo $deptid; ?>&progid=<?php echo $progid; ?>" method="post">
    <table>
        <tr>
            <td>Program ID:</td>
            <td><input type="text" name="progid" value="<?= htmlspecialchars($program['progid']) ?>" readonly></td>
        </tr>
        <tr>
            <td>Program Full Name:</td>
            <td><input type="text" name="progfullname" value="<?= htmlspecialchars($_SESSION['input']['progfullname'] ?? $program['progfullname']) ?>" required></td>
            <td><div class="error"><?php echo $_SESSION['errors']['progfullname'] ?? ''; ?></div></td>
        </tr>
        <tr>
            <td>Program Short Name:</td>
            <td><input type="text" name="progshortname" value="<?= htmlspecialchars($_SESSION['input']['progshortname'] ?? $program['progshortname']) ?>" required></td>
            <td><div class="error"><?php echo $_SESSION['errors']['progshortname'] ?? ''; ?></div></td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit" name="saveProgramChanges" class="btn btn-primary">Save Changes</button>
                <a href="index.php?section=programs&page=programsList&deptid=<?php echo $deptid; ?>" class="btn btn-danger">Exit</a>
            </td>
        </tr>
    </table>
</form>
