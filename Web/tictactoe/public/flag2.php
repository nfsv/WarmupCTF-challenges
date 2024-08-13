<?php
session_start(); // Start or resume the session

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: /login'); // Redirect to login page if not logged in
    exit();
}

if ($_SESSION['username'] === 'tn165') {
    echo "fake_flag_part_2}";
} else {
    header('Location: /game.html');
    exit();
}
?>
