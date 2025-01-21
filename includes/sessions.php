<?php

// Start the session if it's not already active.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Check if the user is authenticated by verifying the presence of an id in the session.
$isConnected = (isset($_SESSION['id']));

if ($isConnected) {
    // Retrieve the username from the session and sanitize it to prevent XSS attacks.
    $username = $_SESSION['username'];
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
}

?>