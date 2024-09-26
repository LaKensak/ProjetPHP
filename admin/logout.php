<?php
session_start();
include("config.php");

// Clear session
$_SESSION = [];
session_destroy();

// Clear cookie
setcookie('auth_token', '', time() - 3600, "/", "", true, true);

header('Location: ../index.php'); // Redirect to login page
exit();
?>
