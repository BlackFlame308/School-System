<?php
session_start();
?>
<h1>Welcome — USJR School Management</h1>
<p>This is a home page for the admin panel. Use the navigation to manage schools, departments, programs, and students.</p>
<ul>
    <li><a href="index.php?section=school&page=schoolList">Schools</a></li>
    <li><a href="index.php?section=department&page=chooseSchool">Departments</a></li>
    <li><a href="index.php?section=programs&page=choosePrograms">Programs</a></li>
    <li><a href="index.php?section=students&page=chooseStudent">Students</a></li>
</ul>

<?php if (!empty($_SESSION['messages'])): ?>
    <div class="messages">
        <?php foreach ($_SESSION['messages'] as $k => $msg): ?>
            <?php if (!empty($msg)): ?>
                <div class="message-<?php echo htmlspecialchars($k); ?>"><?php echo htmlspecialchars($msg); ?></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php $_SESSION['messages'] = []; ?>
<?php endif; ?>
