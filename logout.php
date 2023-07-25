<?php
// Start the session (this should be done at the beginning of every PHP file that uses sessions)
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect the user to the login page (you can change this to any other page you want to redirect to after logout)
header("Location: user.php?login=1");
exit();
?>
