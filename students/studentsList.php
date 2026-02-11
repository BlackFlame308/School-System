<?php
    require_once("data/db.php");
    session_start();
    session_destroy();

    $dbStatement = $db->prepare("SELECT * FROM programs  WHERE progid = :progid");
    $dbStatement->execute(['progid' => $_GET['progid']]);
    $program = $dbStatement->fetch();

    $dbStatement = $db->prepare("SELECT * from students s JOIN programs p WHERE s.studprogid = p.progid AND p.progid = :progid");
    $dbStatement->execute(['progid' => $_GET['progid']]);
    $departments = $dbStatement->fetchAll();
?>

<h1>Student List - <?php echo $program['progfullname']; ?></h1>
<div>
    <h2><a href="index.php?section=students&page=studentCreate&progid=<?php echo $_GET['progid']; ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Create Student</a></h2>
</div>
<table>
    <tr>
        <th>Student  ID</th>
        <th>Student Name</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($departments as $department): ?>
    <tr>
        <td><?php echo $department['studid']; ?></td>
        <td><?php echo $department['studfirstname'] . ' ' . $department['studlastname']; ?></td>
        
        <td>
            <a href="index.php?section=students&page=studentUpdate&progid=<?php echo $_GET['progid']; ?>&studid=<?php echo $department['studid']; ?>" class="btn btn-info"><i class="fas fa-edit"></i> Edit</a>
            <a href="index.php?section=students&page=studentDelete&studid=<?php echo $department['studid']; ?>&progid=<?php echo $_GET['progid']; ?>" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="4">
            <span>
                Total of: <?= count($departments) ?> <?= (count($departments) === 1) ? 'student' : 'students' ?> in the database
            </span>
        </td>
    </tr>
</table>