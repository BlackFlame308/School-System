<?php
require_once(__DIR__ . "/../data/db.php");
session_start();
session_regenerate_id();

$schoolID = $_POST['schoolID'] ?? null;
$schoolFullName = $_POST['schoolFullName'] ?? null;
$schoolShortName = $_POST['schoolShortName'] ?? null;

if (!$schoolID) {
    header("Location: index.php?section=school&page=schoolList", true, 302);
    exit;
}

// If data not passed via POST, fetch from DB
if (!$schoolFullName || !$schoolShortName) {
    $dbStatement = $db->prepare("SELECT * FROM colleges WHERE collid = :schoolID");
    $dbStatement->execute(['schoolID' => $schoolID]);
    $school = $dbStatement->fetch();
    
    if (!$school) {
        header("Location: index.php?section=school&page=schoolList", true, 302);
        exit;
    }
    $schoolFullName = $school['collfullname'];
    $schoolShortName = $school['collshortname'];
}
?>

<h1>Final Confirmation - Delete School</h1>
<p style="color: red; font-weight: bold;">This action CANNOT be undone. Please confirm once more:</p>

<table>
    <tr>
        <td style="width: 10em;">School ID:</td>
        <td style="width: 30em;"><?php echo htmlspecialchars($schoolID); ?></td>
    </tr>
    <tr>
        <td>School Full Name:</td>
        <td><?php echo htmlspecialchars($schoolFullName); ?></td>
    </tr>
    <tr>
        <td>School Short Name:</td>
        <td><?php echo htmlspecialchars($schoolShortName); ?></td>
    </tr>
    <tr>
        <td colspan="2">
            <p><strong>Are you absolutely certain you want to delete this school?</strong></p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <form action="index.php?section=school&page=processSchoolDelete" method="post" style="display:inline;">
                <input type="hidden" name="schoolID" value="<?php echo htmlspecialchars($schoolID); ?>">
                <a href="index.php?section=school&page=schoolList" class="btn btn-primary">
                    Cancel - Do NOT Delete
                </a>
                <button type="submit" name="confirmFinalDelete" class="btn btn-danger">
                    YES, Delete This School Permanently
                </button>
            </form>
        </td>
    </tr>
</table>
