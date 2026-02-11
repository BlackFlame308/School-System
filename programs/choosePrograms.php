<?php
require_once("data/db.php");
session_start();

// Fetch all schools
$dbStatement = $db->prepare("SELECT * FROM colleges");
$dbStatement->execute();
$schools = $dbStatement->fetchAll();

// Handle school selection
if (isset($_POST['selectSchool'])) {
    $_SESSION['school'] = $_POST['schoolID'];

    $dbStatement = $db->prepare("SELECT * FROM departments WHERE deptcollid = :schoolID");
    $dbStatement->bindParam(':schoolID', $_SESSION['school']);
    $dbStatement->execute();
    $programs = $dbStatement->fetchAll();
}
if(isset($_POST['selectDepartment'])){
        $deptid = $_POST["departmentID"];
        header("Location: index.php?section=programs&page=programsList&deptid={$deptid}", true, 301);
    

}
$schoolID = $_SESSION['school'] ?? null;
?>

<h1>Select School</h1>
<form action="index.php?section=programs&page=choosePrograms" method="post">
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
                <!-- Department dropdown (only shows after school is selected) -->
                <select name="departmentID" id="departmentID" class="department-select">
                    <option value="" disabled selected>Select Department</option>
                    <?php foreach ($programs as $program): ?>
                        <option value="<?php echo $program['deptid']; ?>">
                            <?php echo $program['deptfullname']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="selectDepartment" class="btn btn-info">Select Department</button>
            </td>
        </tr>
       
    </table>
</form>
