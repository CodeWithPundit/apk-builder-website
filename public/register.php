<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if($_POST['register']) {
    $db = new SQLite3('../storage/database/apkbuilder.sqlite');
    $username = SQLite3::escapeString($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    if($password !== $confirm) {
        $error = 'Passwords do not match';
    } elseif(strlen($password) < 4) {
        $error = 'Password too short';
    } else {
        $check = $db->querySingle("SELECT id FROM users WHERE username = '$username'");
        if($check) {
            $error = 'Username already exists';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $db->exec("INSERT INTO users (username, password) VALUES ('$username', '$hash')");
            $success = 'Registration successful! <a href="login.php">Login here</a>';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - APK Builder</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-box">
            <h1>🔨 APK Builder</h1>
            <h2>Register</h2>
            
            <?php if($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="success"><?= $success ?></div>
            <?php else: ?>
                <form method="POST">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    <button type="submit" name="register" class="btn-primary">Register</button>
                </form>
                <p>Already have an account? <a href="login.php">Login</a></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
