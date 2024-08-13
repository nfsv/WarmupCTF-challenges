<?php
require 'db.php'; // Include the database connection file

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user inputs
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($username) || empty($password)) {
        header('Location: /signup'); // Redirect back to signup page on error
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Check if username already exists
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        if ($stmt->fetch()) {
            header('Location: /signup'); // Redirect back to signup page if username exists
            exit();
        }

        // Insert new user
        $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // Redirect to login page on success
        header('Location: /login');
        exit();

    } catch (PDOException $e) {
        // Handle database errors
        echo 'Error: ' . $e->getMessage();
    }
} else {
    // If not POST request, redirect to signup page
    header('Location: /signup');
    exit();
}
?>
