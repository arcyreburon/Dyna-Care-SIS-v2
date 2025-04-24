<?php
session_start();

// Destroy the session and clear all session variables
session_unset();
session_destroy();

// Redirect to the login page (index.php, which is in the root folder)
header("Location: ../index.php");  // Go up two levels to reach the root
exit();
?>
