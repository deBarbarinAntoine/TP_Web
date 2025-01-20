<?php
include('utils.php');
$pageTitle = 'Index';
include('sessions.php');
include('header.php');

if ($connected) {
    $message = "Hello " . $username . "!";
} else {
    $message = "Hey visitor!";
}
?>

<h1><?php echo $message; ?></h1>

<?php if ($connected) { ?>

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
