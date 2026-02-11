<?php

$page = 'home/home';

if (isset($_GET['section'])) {
    $section = $_GET['section'];
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

$allowed_pages = [
    'schoolList',
    'departmentList',
    'programsList',
    'studentsList',

    // School CRUD
    'schoolCreate',
    'schoolUpdate',
    'schoolDelete',
    'schoolDeleteConfirm',
    'processSchoolData',
    'processSchoolDelete',
    'processDataChanges',

    // Department CRUD
    'chooseSchool',
    'processSchoolChoice',
    'departmentCreate',
    'processDepartmentData',
    'departmentUpdate',
    'processDepartmentChanges',
    'departmentDelete',
    'processDepartmentDelete',
    'departmentList',

    // Programs CRUD
    'choosePrograms',
    'processProgramsChoice',
    'programsList',
    'programsCreate',
    'processProgramsData',
    'programsUpdate',
    'processProgramsChanges',
    'programsDelete',
    'processProgramsDelete',

    // Students CRUD
    'chooseStudent',
    'processStudentChoice',
    'studentsList',
    'studentCreate',
    'processStudentData',
    'studentUpdate',
    'processStudentChanges',
    'studentDelete',
    'processStudentDelete',

    '500',
    '404'
];

if (in_array($page, $allowed_pages)) {
    if(file_exists("{$section}/{$page}.php")) {
        $file = "{$section}/{$page}.php";
    } else {
        $file = "404/404.php";
    }
} else {
    $file = "home/home.php";
}


require_once $file;
