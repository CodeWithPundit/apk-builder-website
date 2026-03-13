<?php
session_start();
$page = $_GET['page'] ?? 'dashboard';

// Simple routing
if(!isset($_SESSION['user_id']) && !in_array($page, ['login', 'register'])) {
    header('Location: login.php');
    exit;
}

// Load page
switch($page) {
    case 'login': require 'login.php'; break;
    case 'register': require 'register.php'; break;
    case 'file-manager': require 'file-manager.php'; break;
    case 'build': require 'build.php'; break;
    case 'logout': 
        session_destroy(); 
        header('Location: login.php'); 
        break;
    default: require 'dashboard.php';
}
