<?php
    require_once("data/db.php");
    session_start();
    session_destroy();
    
    $dbStatement = $db->prepare("SELECT * FROM departments  WHERE deptid = :deptid");
    $dbStatement->execute(['deptid' => $_GET['deptid']]);
    $department = $dbStatement->fetch();
    echo $_GET['deptid'];


    $dbStatement = $db->prepare("SELECT * from departments d JOIN programs p WHERE d.deptid = p.progcolldeptid AND deptid = :deptid");
    $dbStatement->execute(['deptid' => $_GET['deptid']]);
    $departments = $dbStatement->fetchAll();
?>

<h1>Program List - <?php echo $department['deptfullname']; ?></h1>
<div>
    <h2><a href="index.php?section=programs&page=programsCreate&deptid=<?php echo $_GET['deptid']; ?>" class="btn btn-primary">Create Program</a></h2>
</div>
<table>
    <tr>
        <th>Program ID</th>
        <th>Program Full Name</th>
        <th>Program Short Name</th>
    </tr>
    <?php foreach ($departments as $department): ?>
    <tr>
        <td><?php echo $department['progid']; ?></td>
        <td><?php echo $department['progfullname']; ?></td>
        <td><?php echo $department['progshortname']; ?></td>
        <td>
          
            <a href="index.php?section=programs&page=programsUpdate&deptid=<?php echo $department['deptid']; ?>&progid=<?php echo $department['progid']; ?>" class="btn btn-info">Update</a>
            <a href="index.php?section=programs&page=programsDelete&progid=<?php echo $department['progid']; ?>" class="btn btn-danger">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="4">
            <span>
                Total of: <?= count($departments) ?> <?= (count($departments) === 1) ? 'department' : 'departments' ?> in the database
            </span>
        </td>
    </tr>
</table>