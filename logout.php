<?php
// Start session to manage session data
session_start();

// Prevent caching of the page after logout to prevent back button access
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

// Destroy the session completely
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session completely

// Redirect to the login page
header("Location: index.php"); // Redirect user to the login page
exit;
?>
