<?php
// This page serves as the "Home" page and ensures content visibility based on user connection status.

// Set page variables
$pageTitle = 'Home';

// Utility functions such as redirection and password handling
include_once('includes/utils.php');

// Session management to check user connection status
include_once('includes/sessions.php');

// Header of the webpage with common elements
include_once('includes/header.php');

authenticatedGuard($isConnected);

// Content visible only to connected (logged-in) users
?>

    <p>Hey, still there <?php echo $username; ?>?</p>
    <a href="index.php">Return</a>

<?php

// Footer of the webpage with common elements
include "footer.php";

?>