<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Ensure no output before JSON
header('Content-Type: application/json');

// Check for the correct session variable
if (isset($_SESSION['user_id'])) { // Updated to match login_process.php
    echo json_encode(['loggedIn' => true]);
} else {
    echo json_encode(['loggedIn' => false]);
}
?>