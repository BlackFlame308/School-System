<?php
    require_once("data/db.php");
    session_start();
    session_destroy();

    $dbStatement = $db->prepare("SELECT * FROM colleges");
    $dbStatement->execute();
    $schools = $dbStatement->fetchAll();
?>

<h1>Select School</h1>
<form action="index.php?section=department&page=processSchoolChoice" method="post">
    <table>
        <tr>
            <td>
                <select name="schoolID" id="schoolID" class="school-select">
                    <option value=null selected hidden disabled>Select School</option>
                    <?php foreach ($schools as $school): ?>
                    <option value="<?php echo $school['collid']; ?>"><?php echo $school['collfullname']; ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="selectSchool" class="btn btn-info">Select School</button>
            </td>
        </tr>
        <tr>
            <td>
                
            </td>
        </tr>
    </table>
</form>
