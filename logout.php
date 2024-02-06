<?php
// Start session from here
session_start();

// Unset all of the session variable
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: index.php")
exit;
?>
