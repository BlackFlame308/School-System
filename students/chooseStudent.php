<?php
require_once("data/db.php");
session_start();

// Reset selections on fresh GET visit
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    unset($_SESSION['school'], $_SESSION['department'], $_SESSION['program']);
}

// Initialize arrays
$schools = [];
$departments = [];
$programs = [];

// Fetch all schools (always available)
$dbStatement = $db->prepare("SELECT * FROM colleges");
$dbStatement->execute();
$schools = $dbStatement->fetchAll();

// Handle school selection
if (isset($_POST['selectSchool'])) {
    $_SESSION['school'] = $_POST['schoolID'];
}

// Handle department selection
if (isset($_POST['selectDepartment'])) {
    $_SESSION['department'] = $_POST['departmentID'];
}

// Handle program selection
if (isset($_POST['selectProgram'])) {
    $_SESSION['program'] = $_POST['programID'];
    $deptid = $_SESSION['department'];
    header("Location: index.php?section=students&page=studentsList&progid={$_SESSION['program']}", true, 301);
    exit;
}

// Determine selected values
$schoolID = $_SESSION['school'] ?? null;
$departmentID = $_SESSION['department'] ?? null;
$programID = $_SESSION['program'] ?? null;

// Always fetch departments if a school is selected
if ($schoolID) {
    $dbStatement = $db->prepare("SELECT * FROM departments WHERE deptcollid = :schoolID");
    $dbStatement->bindParam(':schoolID', $schoolID);
    $dbStatement->execute();
    $departments = $dbStatement->fetchAll();
}

// Always fetch programs if a department is selected
if ($departmentID) {
    $dbStatement = $db->prepare("SELECT * FROM programs WHERE progcolldeptid = :deptID");
    $dbStatement->bindParam(':deptID', $departmentID);
    $dbStatement->execute();
    $programs = $dbStatement->fetchAll();
}
?>

<h1>Select School</h1>
<form action="index.php?section=students&page=chooseStudent" method="post">
    <table>
        <tr>
            <td>
                <!-- School dropdown -->
                <select name="schoolID" id="schoolID" class="school-select">
                    <option value="" disabled <?php echo $schoolID === null ? 'selected' : ''; ?>>Select School</option>
                    <?php foreach ($schools as $school): ?>
                        <option value="<?php echo $school['collid']; ?>"
                            <?php echo ($schoolID == $school['collid']) ? 'selected' : ''; ?>>
                            <?php echo $school['collfullname']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="selectSchool" class="btn btn-info">Select School</button>
            </td>
        </tr>

        <tr>
            <td>
                <!-- Department dropdown -->
                <select name="departmentID" id="departmentID" class="department-select">
                    <option value="" disabled <?php echo $departmentID === null ? 'selected' : ''; ?>>Select Department</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo $department['deptid']; ?>"
                            <?php echo ($departmentID == $department['deptid']) ? 'selected' : ''; ?>>
                            <?php echo $department['deptfullname']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="selectDepartment" class="btn btn-info">Select Department</button>
            </td>
        </tr>

        <tr>
            <td>
                <!-- Program dropdown -->
                <select name="programID" id="programID" class="program-select">
                    <option value="" disabled <?php echo $programID === null ? 'selected' : ''; ?>>Select Program</option>
                    <?php foreach ($programs as $program): ?>
                        <option value="<?php echo $program['progid']; ?>"
                            <?php echo ($programID == $program['progid']) ? 'selected' : ''; ?>>
                            <?php echo $program['progfullname']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="selectProgram" class="btn btn-info">Select Program</button>
            </td>
        </tr>
    </table>
</form>
