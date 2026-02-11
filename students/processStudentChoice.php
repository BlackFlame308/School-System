<?php
if($_POST){
    $schoolID = $_POST['schoolID'] ?? null;

    var_dump($schoolID);

    $origin = $_SERVER['HTTP_REFERER'];

    if($schoolID === null){
        header("Location: {$origin}", true, 301);
    } else {
        header("Location: index.php?section=department&page=departmentList&deptcollid={$schoolID}", true, 301);
    }

}