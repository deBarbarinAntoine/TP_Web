<?php
// Main landing page with user connection-specific content

// Set page variables
$pageTitle = 'Index';

// Utility functions such as redirection and password handling
include_once('includes/utils.php');

// Session management to check user connection status
include_once('includes/sessions.php');

// Header of the webpage with common elements
include_once('includes/header.php');

if ($isConnected) {
    $message = "Hello " . $username . "!";
} else {
    $message = "Hey visitor!";
}
?>

<h1><?php echo $message; ?></h1>

<?php if ($isConnected) { ?>

    <a href="home.php">Home</a>
    <form method="post" action="logout.php">
        <input type="hidden" name="logout" value="Logout">
        <button type="submit">Logout</button>
    </form>

    <?php } else { ?>

    <a href="login.php">Login</a>

<?php
}

include('footer.php');
?>
