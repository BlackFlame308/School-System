<?php
session_start();
session_regenerate_id();

$studid = $_GET['studid'] ?? null;
$progid = $_GET['progid'] ?? null;
if (!$studid) {
    header("Location: index.php?section=programs&page=choosePrograms", true, 301);
    exit;
}

require_once(__DIR__ . "/../data/db.php");

$dbStatement = $db->prepare("SELECT * FROM students WHERE studid = :studid");
$dbStatement->execute(['studid' => $studid]);
$student = $dbStatement->fetch();
?>

<h1>Delete Student - <?php echo htmlspecialchars($student['studfirstname'] . ' ' . $student['studlastname']); ?></h1>
<p>Are you sure you want to delete this student?</p>
<form action="index.php?section=students&page=processStudentDelete&studid=<?php echo $studid; ?>&progid=<?php echo $progid; ?>" method="post">
    <button type="submit" name="confirmDelete" class="btn btn-danger">Yes, Delete</button>
    <a href="javascript:history.back()" class="btn">Cancel</a>
</form>
