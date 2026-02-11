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
?>

<h1>Delete Department - <?php echo htmlspecialchars($department['collfullname']); ?></h1>
<div class="message">
    <?php echo $_SESSION['messages']['deleteSuccess'] ?? $_SESSION['messages']['deleteError'] ?? ''; ?>
</div>
<form action="index.php?section=department&page=processDepartmentDelete&deptid=<?php echo $deptid; ?>" method="post">
    <table>
        <tr>
            <td style="width: 10em;">Department ID:</td>
            <td style="width: 30em;"><input type="text" id="deptID" name="deptID" value="<?php echo htmlspecialchars($department['deptid']); ?>" readonly class="data-input"></td>
        </tr>
        <tr>
            <td>Department Full Name:</td>
            <td><input type="text" id="deptFullName" name="deptFullName" value="<?php echo htmlspecialchars($department['deptfullname']); ?>" readonly class="data-input"></td>               
        </tr>
        <tr>
            <td>Department Short Name:</td>
            <td><input type="text" id="deptShortName" name="deptShortName" value="<?php echo htmlspecialchars($department['deptshortname']); ?>" readonly class="data-input"></td>              
        </tr>
        <tr>
            <td colspan="2">
                <a href="index.php?section=department&page=departmentList&deptcollid=<?php echo $department['deptcollid']; ?>" class="btn btn-primary">
                    Cancel Operation
                </a>                
                <button type="submit" name="confirmDelete" class="btn btn-danger">
                    Confirm Delete
                </button>
            </td>
        </tr>
    </table>
</form>





