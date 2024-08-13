<?php
$dsn = 'sqlite:database/db.sqlite';
$username = null;
$password = null;

try {
    // Establish a connection to the SQLite database
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the 'users' table exists
    $result = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
    $tableExists = $result->fetch();

    if (!$tableExists) {
        // Table does not exist, so create the table
        $createTableSql = "CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            humanScore INT NOT NULL DEFAULT 0 CHECK (humanScore >= 0),
            computerScore INT NOT NULL DEFAULT 0 CHECK (computerScore >= 0)
        )";
        $pdo->exec($createTableSql);

        $test_name = 'tn165';
        // This should be read from file. To make it simple, i put it here.
        $test_password = "\x00\x67\x6f\x6f\x64\x20\x6d\x6f\x72\x2e\x2e\x2eREDACTED\x00";
        $test_hashedPassword = password_hash($test_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
        $stmt->bindParam(':username', $test_name);
        $stmt->bindParam(':password', $test_hashedPassword);
        $stmt->execute();

    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
