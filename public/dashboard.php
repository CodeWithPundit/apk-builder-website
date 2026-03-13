<?php
require_once '../src/init.php';
$projects = $db->query("SELECT * FROM projects WHERE user_id = {$_SESSION['user_id']}");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?= $_SESSION['username'] ?></h1>
        <nav>
            <a href="?page=file-manager">📁 File Manager</a>
            <a href="?page=build">⚡ New Build</a>
            <a href="logout.php">🚪 Logout</a>
        </nav>
        
        <h2>Your Projects</h2>
        <div class="projects">
            <?php while($project = $projects->fetchArray()): ?>
                <div class="project-card">
                    <h3><?= $project['name'] ?></h3>
                    <p><?= $project['package_name'] ?></p>
                    <a href="?page=file-manager&project=<?= $project['id'] ?>">Open</a>
                    <a href="?page=build&project=<?= $project['id'] ?>">Build</a>
                </div>
            <?php endwhile; ?>
        </div>
        
        <form method="POST" action="build.php">
            <h3>New Project</h3>
            <input type="text" name="name" placeholder="Project Name" required>
            <input type="text" name="package" placeholder="com.example.app" required>
            <button type="submit" name="create_project">Create</button>
        </form>
    </div>
</body>
</html>
