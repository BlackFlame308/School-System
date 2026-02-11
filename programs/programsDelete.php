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

<h1>Delete Program - <?php echo htmlspecialchars($program['progfullname']); ?></h1>
<p>Are you sure you want to delete this program?</p>
<form action="index.php?section=programs&page=processProgramsDelete&progid=<?php echo $progid; ?>" method="post">
    <button type="submit" name="confirmDelete" class="btn btn-danger">Yes, Delete</button>
    <a href="javascript:history.back()" class="btn">Cancel</a>
</form>
