<?php
// This script handles the logout process. It verifies if the user is logged in,
// destroys their session, clears the session cookie, and displays a logout confirmation message.


// Utility functions such as redirection and password handling
include('includes/utils.php');

// Session management to check user connection status
include('includes/sessions.php');

authenticatedGuard($isConnected);

if (isset($_POST["logout"])) {
    $username = $_SESSION["username"];
    logout();

    session_start();
    $_SESSION['message'] = "You are now logged out!";
    ?>

    <p>Bye <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>!</p>
    <a href="index.php">Return</a>

    <?php
} else {
    redirect("index.php");
}
?>