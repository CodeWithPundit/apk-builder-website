<?php
require_once '../src/init.php';
require_once '../src/helpers/BuildHelper.php';

if($_POST['create_project']) {
    $name = SQLite3::escapeString($_POST['name']);
    $package = SQLite3::escapeString($_POST['package']);
    
    $db->exec("INSERT INTO projects (user_id, name, package_name) VALUES ({$_SESSION['user_id']}, '$name', '$package')");
    $project_id = $db->lastInsertRowID();
    
    // Copy template
    $template = '../android-templates/basic-app';
    $target = "../storage/projects/{$_SESSION['user_id']}/$project_id";
    mkdir($target, 0777, true);
    
    // Simple copy
    shell_exec("cp -r $template/* $target/");
    
    header("Location: file-manager.php?project=$project_id");
    exit;
}

if($_POST['build_project']) {
    $project_id = $_POST['project_id'];
    $project = $db->querySingle("SELECT * FROM projects WHERE id = $project_id", true);
    
    $project_path = "../storage/projects/{$_SESSION['user_id']}/{$project['id']}";
    $build_path = "../storage/builds/{$_SESSION['user_id']}/{$project['id']}/" . time() . ".apk";
    
    mkdir(dirname($build_path), 0777, true);
    
    $result = BuildHelper::build($project_path, $build_path);
    
    if($result['success']) {
        $db->exec("INSERT INTO builds (project_id, user_id, file_path, status) VALUES ($project_id, {$_SESSION['user_id']}, '$build_path', 'success')");
        $message = "Build successful! <a href='$build_path'>Download APK</a>";
    } else {
        $message = "Build failed: " . $result['error'];
    }
}

$projects = $db->query("SELECT * FROM projects WHERE user_id = {$_SESSION['user_id']}");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Build APK</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Build APK</h1>
        <a href="dashboard.php">← Back</a>
        
        <?php if($message): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>
        
        <h2>Select Project to Build</h2>
        <?php while($project = $projects->fetchArray()): ?>
            <div class="project-item">
                <h3><?= $project['name'] ?></h3>
                <p><?= $project['package_name'] ?></p>
                <form method="POST">
                    <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                    <button type="submit" name="build_project">Build APK</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
