<?php
require_once(__DIR__ . "/../data/db.php");
session_start();
session_regenerate_id();

$deptid = $_GET['deptid'] ?? null;

if (!$deptid) {
    header("Location: index.php?section=department&page=chooseSchool", true, 301);
    exit;
}

$dbStatement = $db->prepare("SELECT d.*, c.collfullname FROM departments d JOIN colleges c ON d.deptcollid = c.collid WHERE d.deptid = :deptid");
$dbStatement->execute(['deptid' => $deptid]);
$department = $dbStatement->fetch();

if (!$department) {
    header("Location: index.php?section=department&page=chooseSchool", true, 301);
    exit;
}

// Pre-fill session with current values if not set
if (!isset($_SESSION['input']['deptFullName'])) {
    $_SESSION['input']['deptFullName'] = $department['deptfullname'];
    $_SESSION['input']['deptShortName'] = $department['deptshortname'];
}
?>

<h1>Update Department - <?php echo htmlspecialchars($department['collfullname']); ?></h1>
<div class="message">
    <?php echo $_SESSION['messages']['updateSuccess'] ?? $_SESSION['messages']['updateError'] ?? ''; ?>
</div>
<form action="index.php?section=department&page=processDepartmentChanges&deptid=<?php echo $deptid; ?>" method="post">
    <table>
        <tr>
            <td style="width: 10em;">Department ID:</td>
            <td style="width: 30em;"><input type="text" id="deptID" name="deptID" value="<?php echo htmlspecialchars($department['deptid']); ?>" readonly class="data-input"></td>
        </tr>
        <tr>
            <td>Department Full Name:</td>
            <td><input type="text" id="deptFullName" name="deptFullName" value="<?php echo htmlspecialchars($_SESSION['input']['deptFullName'] ?? $department['deptfullname']); ?>" class="data-input"></td>
            <td>
                <div class="error">
                    <?php echo $_SESSION['errors']['deptFullName'] ?? ''; ?>
                </div>
            </td>                
        </tr>
        <tr>
            <td>Department Short Name:</td>
            <td><input type="text" id="deptShortName" name="deptShortName" value="<?php echo htmlspecialchars($_SESSION['input']['deptShortName'] ?? $department['deptshortname']); ?>" class="data-input"></td>
            <td>
                <div class="error">
                    <?php echo $_SESSION['errors']['deptShortName'] ?? ''; ?>
                </div>
            </td>                
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit" name="saveChanges" class="btn btn-primary">
                    Update Department Entry
                </button>
                <button type="submit" name="clearChanges" class="btn">
                    Reset Form
                </button>
                <a href="index.php?section=department&page=departmentList&deptcollid=<?php echo $department['deptcollid']; ?>" class="btn btn-danger">
                    Exit
                </a>
            </td>
        </tr>
    </table>
</form>





