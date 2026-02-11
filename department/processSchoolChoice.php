<?php
if ($_POST) {
    $schoolID = $_POST['schoolID'] ?? null;

    $origin = $_SERVER['HTTP_REFERER'] ?? 'index.php?section=department&page=chooseSchool';

    if (empty($schoolID)) {
        header("Location: {$origin}", true, 302);
        exit;
    }

    header("Location: index.php?section=department&page=departmentList&deptcollid=" . urlencode($schoolID), true, 302);
    exit;
}