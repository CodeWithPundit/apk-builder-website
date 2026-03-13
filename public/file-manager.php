<?php
require_once '../src/init.php';
$project_id = $_GET['project'] ?? 0;
$project = $db->querySingle("SELECT * FROM projects WHERE id = $project_id AND user_id = {$_SESSION['user_id']}", true);

if(!$project) {
    header('Location: dashboard.php');
    exit;
}

$project_path = "../storage/projects/{$_SESSION['user_id']}/{$project['id']}";
$current_file = $_GET['file'] ?? '';
$file_content = '';

if($current_file && file_exists($project_path . '/' . $current_file)) {
    $file_content = file_get_contents($project_path . '/' . $current_file);
}

// Handle file save
if($_POST['save']) {
    $file = $_POST['file'];
    $content = $_POST['content'];
    file_put_contents($project_path . '/' . $file, $content);
    $message = "File saved!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>File Manager</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Editing: <?= $project['name'] ?></h1>
        <a href="dashboard.php">← Back</a>
        
        <?php if($message): ?>
            <div class="success"><?= $message ?></div>
        <?php endif; ?>
        
        <div class="file-manager">
            <div class="sidebar">
                <h3>Files</h3>
                <ul>
                    <?php
                    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($project_path));
                    foreach($files as $file) {
                        if($file->isFile()) {
                            $relative = str_replace($project_path . '/', '', $file);
                            echo "<li><a href='?page=file-manager&project=$project_id&file=$relative'>" . basename($relative) . "</a></li>";
                        }
                    }
                    ?>
                </ul>
            </div>
            
            <div class="editor">
                <?php if($current_file): ?>
                    <form method="POST">
                        <input type="hidden" name="file" value="<?= $current_file ?>">
                        <textarea name="content" rows="30" style="width:100%; font-family:monospace;"><?= htmlspecialchars($file_content) ?></textarea>
                        <button type="submit" name="save">Save</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
