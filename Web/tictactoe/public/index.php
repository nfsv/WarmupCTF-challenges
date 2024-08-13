<?php
session_start(); // Start the session

// Check if the user is logged in by checking a session variable
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    // User is logged in, redirect to the game page
    header("Location: /game");
    exit();
} else {
    // User is not logged in, redirect to the login page
    header("Location: /login");
    exit();
}
?>
