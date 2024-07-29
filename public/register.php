<?php
require_once 'UserAuthentication.php';
require_once 'config.php';

// Create a new database connection
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check for connection errors
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Create an instance of UserAuthentication
$auth = new UserAuthentication($db);

// Sample data for user registration
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Attempt to register the user
$result = $auth->register($username, $password);

// Output the result of the registration attempt
echo $result;
?>
