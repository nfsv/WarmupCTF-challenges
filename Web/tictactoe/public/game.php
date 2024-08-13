<?php
session_start(); // Start the session

// Check if the user is logged in by checking a session variable
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: /login'); // Redirect to login page if not logged in
    exit();
}

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: /logout");
    exit();
}
else {
    // User is logged in, load game
    $username = $_SESSION['username'];
    $humanScore = (int)$_SESSION['humanScore'];
    $computerScore = (int)$_SESSION['computerScore'];
    
    // Load the contents of index.html
    $html = file_get_contents('static/game.html');

    // Modify the paths for CSS and JS
    $newCssPath = 'static/game.css'; // Update this to the desired path
    $newJsPath = 'static/game.js'; // Update this to the desired path

    // Replace the original paths with the new paths
    $html = str_replace('game.css', $newCssPath, $html);
    $html = str_replace('game.js', $newJsPath, $html);
    
    // Use a regular expression to replace the content of the placeholder elements
    // This pattern looks for the content of the b tag with id="username-placeholder"
    $html = preg_replace(
        '/(<b id="username-placeholder">)[^<]*(<\/b>)/',
        '$1' . htmlspecialchars($username) . '$2',
        $html
    );
    
    // Embed the scores in a <script> tag
    $html = preg_replace(
        '/<\/body>/',
        "<script>
        // Embed scores from PHP
        humanScore = $humanScore;
        computerScore = $computerScore;
        humanScoreElement.textContent = humanScore;
        computerScoreElement.textContent = computerScore; </script>",
        $html
    );
    
    // Output the modified HTML
    echo $html;
    
    exit();
}
?>