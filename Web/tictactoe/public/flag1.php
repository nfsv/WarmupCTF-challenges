<?php
session_start(); // Start or resume the session

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: /login'); // Redirect to login page if not logged in
    exit();
}

if ((int)$_SESSION['humanScore'] - (int)$_SESSION['computerScore'] > 3) {
    echo "WARMUP{fake_flag_part_1_";
} else {
    header('Location: /game.html');
    exit();
}
?>
