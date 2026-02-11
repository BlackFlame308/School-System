<?php
   require_once("data/db.php");
   session_start();
   session_destroy();

   $limit = 5;

   $dbStatement = $db->prepare("SELECT * FROM colleges");
   $dbStatement->execute();
   $totalSchools = $dbStatement->rowCount();

   $totalPages = ceil($totalSchools / $limit);


   if(!isset($_GET['pgSection']) || !is_numeric($_GET['pgSection'])) {
       $currentPage = 1;
   } else {
       $currentPage = intval($_GET['pgSection']);
   }

   $offset = ($currentPage - 1) * $limit;

   $dbStatement = $db->prepare("SELECT * FROM colleges ORDER BY collid LIMIT :offset, :limit;");
   $dbStatement->bindParam('offset', $offset, PDO::PARAM_INT);
   $dbStatement->bindParam('limit', $limit, PDO::PARAM_INT);
   $dbStatement->execute();

//    $dbStatement->execute(['offset' => $offset, 'limit' => $limit]);
   
   $schools = $dbStatement->fetchAll();
?>

<h1>School List</h1>
<div class="message">
    <?php 
        if (isset($_SESSION['messages']['deleteSuccess'])) {
            echo "<span style='color:green;'>" . $_SESSION['messages']['deleteSuccess'] . "</span>";
            unset($_SESSION['messages']['deleteSuccess']);
        }
    ?>
</div>
<div>
    <h2><a href="index.php?section=school&page=schoolCreate" class="btn btn-primary">Create School</a></h2>
</div>
<table>
    <tr>
        <th>School ID</th>
        <th>School Full Name</th>
        <th>School Short Name</th>
    </tr>
    <?php foreach ($schools as $school): ?>
    <tr>
        <td><?php echo $school['collid']; ?></td>
        <td><?php echo $school['collfullname']; ?></td>
        <td><?php echo $school['collshortname']; ?></td>
        <td>
            <a href="index.php?section=school&page=schoolUpdate&collid=<?php echo $school['collid']; ?>" class="btn btn-info">Update</a>
            <a href="index.php?section=school&page=schoolDelete&collid=<?php echo $school['collid']; ?>" class="btn btn-danger">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="2">
            <span>
                Total of: <?= $totalSchools ?> schools in the database
            </span>
        </td>
        <td colspan="2">
          <?php if($totalPages > 1): ?>  
            <?php if ($currentPage > 1): ?>
                <a href="index.php?section=school&page=schoolList&pgSection=<?= $currentPage - 1 ?>" class="btn btn-primary">Previous</a>
            <?php else: ?>
                <span>Previous</span>
            <?php endif; ?>
            <?php if ($currentPage < $totalPages): ?>
                <a href="index.php?section=school&page=schoolList&pgSection=<?= $currentPage + 1 ?>" class="btn btn-primary">Next</a>
            <?php else: ?>
                <span>Next</span>
            <?php endif; ?>
          <?php endif; ?>  
        </td>
    </tr>
</table>
