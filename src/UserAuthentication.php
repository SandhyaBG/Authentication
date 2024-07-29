<?php
require_once 'config.php'; // Include the database connection configuration

class UserAuthentication {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register a new user
    public function register($username, $password) {
        // Check if username is empty
        if (empty($username)) {
            return 'Username cannot be empty';
        }

        // Check if password is empty
        if (empty($password)) {
            return 'Password cannot be empty';
        }

        // Check if username already exists
        $query = "SELECT id FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            return 'Username already exists';
        }

        // Hash the password and save the new user
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $username, $hashedPassword);

        if ($stmt->execute()) {
            return 'User registered successfully';
        } else {
            return 'Error registering user: ' . $stmt->error;
        }
    }

    // Log in an existing user
    public function login($username, $password) {
        // Check if username or password is empty
        if (empty($username) || empty($password)) {
            return 'Username and password cannot be empty';
        }

        // Find the user by username
        $query = "SELECT id, password FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($userId, $hashedPassword);
        $stmt->fetch();

        if ($stmt->num_rows === 0) {
            return 'Invalid username or password';
        }

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Generate a simple token (for demonstration purposes)
            $token = bin2hex(random_bytes(16));
            return json_encode(['token' => $token]);
        } else {
            return 'Invalid username or password';
        }
    }
}
?>
