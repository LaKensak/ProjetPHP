<?php
session_start();
include("config.php");

if (isset($_SESSION['user_id'])) {
    // User is logged in through session
    $user_id = $_SESSION['user_id'];
} elseif (isset($_COOKIE['auth_token'])) {
    // Check for a token in the cookie
    $token = $_COOKIE['auth_token'];

    $sql = "SELECT * FROM users WHERE token = :token";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    if ($user) {
        // If a valid token is found, log in the user
        $_SESSION['user_id'] = $user['id']; // Set session
        $_SESSION['username'] = $user['username']; // Optionally store the username
    } else {
        // Invalid token, you may want to clear the cookie
        setcookie('auth_token', '', time() - 3600, "/", "", true, true); // Expire the cookie
    }
}

// Optionally, you can now include a user dashboard or greeting based on logged-in status
?>
