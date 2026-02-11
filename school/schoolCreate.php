<?php
   session_start();
   session_regenerate_id();
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Create</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body> -->
    <h1>School Create</h1>
    <span>
        <?php echo $_SESSION['messages']['createSuccess'] ?? null; ?>
        <?php echo $_SESSION['messages']['createError'] ?? null; ?>
    </span>
    <form action="index.php?section=school&page=processSchoolData" method="post">
        <table>
            <tr>
                <td style="width: 10em;">School ID:</td>
                <td style="width: 30em;"><input type="text" id="schoolID" name="schoolID" value="<?= $_SESSION['input']['schoolID'] ?? null; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['schoolID'] ?? null; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>School Full Name:</td>
                <td><input type="text" id="schoolFullName" name="schoolFullName" value="<?= $_SESSION['input']['schoolFullName'] ?? null; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['schoolFullName'] ?? null; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>School Short Name:</td>
                <td><input type="text" id="schoolShortName" name="schoolShortName" value="<?= $_SESSION['input']['schoolShortName'] ?? null; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['schoolShortName'] ?? null; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit" name="saveNewSchoolEntry" class="btn">
                        Save New School Entry
                    </button>
                    <button type="submit" name="clearEntries" class="btn">
                        Reset Form
                    </button>
                    <a href="index.php?section=school&page=schoolList" class="btn btn-danger">
                        Exit
                    </a>
                </td>
            </tr>
        </table>
    </form>    
<!-- </body>
</html> -->