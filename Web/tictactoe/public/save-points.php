<?php
require 'db.php'; // Include the database connection file
session_start(); // Start or resume the session

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: /login'); // Redirect to login page if not logged in
    exit();
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read and decode the JSON data from php://input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Retrieve and sanitize user inputs
    $username = $_SESSION['username'];
    $new_humanScore = isset($data['humanScore']) ? (int)$data['humanScore'] : 0;
    $new_computerScore = isset($data['computerScore']) ? (int)$data['computerScore'] : 0;

    // Basic validation
    if ($new_humanScore < 0 || $new_computerScore < 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid score']);
        exit();
    }

    try {
        // Update the user's score and computer score in the database
        $stmt = $pdo->prepare('UPDATE users SET humanScore = :humanScore, computerScore = :computerScore WHERE username = :username');
        $stmt->bindParam(':humanScore', $new_humanScore, PDO::PARAM_INT);
        $stmt->bindParam(':computerScore', $new_computerScore, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Return success response
        echo json_encode(['success' => true]);
        
        $_SESSION['humanScore'] = $new_humanScore;
        $_SESSION['computerScore'] = $new_computerScore;
        exit();

    } catch (PDOException $e) {
        // Handle database errors
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit();
    }
} else {
    // If not POST request, redirect to game page
    header('Location: /game');
    exit();
}
?>
