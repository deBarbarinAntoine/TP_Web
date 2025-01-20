<?php
include('utils.php');
include('sessions.php');

if (!$connected) {
    redirect("index.php");
} else {
    if (isset($_POST["logout"])) {
        $username = $_SESSION["username"];
        session_unset();
        session_destroy();
        setcookie(session_name(), "", strtotime("-1 day"));
        ?>

        <p>Bye <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>!</p>
        <a href="index.php">Return</a>

        <?php
    } else {
        redirect("index.php");
    }
}