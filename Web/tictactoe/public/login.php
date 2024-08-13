<?php
require 'db.php'; // Include the database connection file
session_start(); // Start a new session or resume the existing one

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user inputs
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        // Check if the username exists
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['username'] = $username;
            $_SESSION['loggedin'] = true;
            $_SESSION['humanScore'] = (int)$user['humanScore'];
            $_SESSION['computerScore'] = (int)$user['computerScore'];

            // Redirect to the game page on successful login
            header('Location: /game');
            exit();
        } else {
            // Redirect back to login page on failure
            header('Location: /login');
            exit();
        }

    } catch (PDOException $e) {
        // Handle database errors
        echo 'Error: ' . $e->getMessage();
    }
} else {
    // If not POST request, redirect to login page
    header('Location: /login');
    exit();
}
?>
