<?php
    require_once("data/db.php");
    session_start();
    session_regenerate_id();

    $schoolID = $_GET['collid'];

    $dbStatement = $db->prepare("SELECT * FROM colleges WHERE collid = :schoolID");
    $dbStatement->execute(['schoolID' => $schoolID]);
    $school = $dbStatement->fetch();
?>
<h1>School Delete</h1>
<div class="message">
    <?php 
        if (isset($_SESSION['messages']['deleteSuccess'])) {
            echo "<span style='color:green;'>" . $_SESSION['messages']['deleteSuccess'] . "</span>";
            unset($_SESSION['messages']['deleteSuccess']);
        }
        if (isset($_SESSION['messages']['deleteError'])) {
            echo "<span style='color:red;'>" . $_SESSION['messages']['deleteError'] . "</span>";
            unset($_SESSION['messages']['deleteError']);
        }
    ?>
</div>
    <table>
        <tr>
            <td style="width: 10em;">School ID:</td>
            <td style="width: 30em;"><input type="text" id="schoolID" name="schoolID" value="<?php echo $school['collid']; ?>" readonly class="data-input"></td>
        </tr>
        <tr>
            <td>School Full Name:</td>
            <td><input type="text" id="schoolFullName" name="schoolFullName" value="<?php echo $school['collfullname']; ?>" readonly class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['schoolFullName'] ?? null; ?>
                </span>
            </td>                
        </tr>
        <tr>
            <td>School Short Name:</td>
            <td><input type="text" id="schoolShortName" name="schoolShortName" value="<?php echo $school['collshortname']; ?>" readonly class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['schoolShortName'] ?? null; ?>
                </span>
            </td>                
        </tr>
        <tr>
            <td colspan="2">
                <a href="index.php?section=school&page=schoolList" class="btn btn-primary">
                    Cancel Operation
                </a>                
                <form action="index.php?section=school&page=schoolDeleteConfirm" method="post" style="display:inline;">
                    <input type="hidden" name="schoolID" value="<?php echo htmlspecialchars($school['collid']); ?>">
                    <input type="hidden" name="schoolFullName" value="<?php echo htmlspecialchars($school['collfullname']); ?>">
                    <input type="hidden" name="schoolShortName" value="<?php echo htmlspecialchars($school['collshortname']); ?>">
                    <button type="submit" class="btn btn-warning">
                        Proceed to Final Confirmation
                    </button>
                </form>
            </td>
        </tr>
    </table>    
