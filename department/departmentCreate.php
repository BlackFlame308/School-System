<?php
session_start();
session_regenerate_id();

$collid = $_GET['deptcollid'] ?? null;

if (!$collid) {
    header("Location: index.php?section=department&page=chooseSchool", true, 301);
    exit;
}

require_once(__DIR__ . "/../data/db.php");

$dbStatement = $db->prepare("SELECT * FROM colleges WHERE collid = :collid");
$dbStatement->execute(['collid' => $collid]);
$school = $dbStatement->fetch();
?>

<h1>Create Department - <?php echo htmlspecialchars($school['collfullname']); ?></h1>
<div class="message">
    <?php echo $_SESSION['messages']['createSuccess'] ?? $_SESSION['messages']['createError'] ?? ''; ?>
</div>
<form action="index.php?section=department&page=processDepartmentData&deptcollid=<?php echo $collid; ?>" method="post">
    <table>
        <tr>
            <td style="width: 10em;">Department ID:</td>
            <td style="width: 30em;"><input type="text" id="deptID" name="deptID" value="<?= htmlspecialchars($_SESSION['input']['deptID'] ?? ''); ?>" class="data-input" pattern="[0-9]+" title="Department ID must be numeric" required></td>
            <td>
                <div class="error">
                    <?php echo $_SESSION['errors']['deptID'] ?? ''; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td>Department Full Name:</td>
            <td><input type="text" id="deptFullName" name="deptFullName" value="<?= htmlspecialchars($_SESSION['input']['deptFullName'] ?? ''); ?>" class="data-input" pattern="[A-Za-z\s\-\'\.&]+" title="Use only letters, spaces, hyphens, apostrophes, dots, and ampersands" required></td>
            <td>
                <div class="error">
                    <?php echo $_SESSION['errors']['deptFullName'] ?? ''; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td>Department Short Name:</td>
            <td><input type="text" id="deptShortName" name="deptShortName" value="<?= htmlspecialchars($_SESSION['input']['deptShortName'] ?? ''); ?>" class="data-input" pattern="[A-Za-z\s\-\'\.&]+" title="Use only letters, spaces, hyphens, apostrophes, dots, and ampersands" required></td>
            <td>
                <div class="error">
                    <?php echo $_SESSION['errors']['deptShortName'] ?? ''; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit" name="saveNewDepartmentEntry" class="btn btn-primary">
                    Save New Department Entry
                </button>
                <button type="submit" name="clearEntries" class="btn">
                    Reset Form
                </button>
                <a href="index.php?section=department&page=departmentList&deptcollid=<?php echo $collid; ?>" class="btn btn-danger">
                    Exit
                </a>
            </td>
        </tr>
    </table>
</form>





