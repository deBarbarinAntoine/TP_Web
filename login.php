<?php
include('utils.php');
$pageTitle = 'Login';
include('sessions.php');
include('header.php');

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    if (empty($username)) {
        $error = 'A valid username is required';
    }
    if (strlen($username) < 3 || strlen($username) > 16) {
        $error = 'A valid username is required';
    }
    $username = stripslashes($username);

    if (!isset($error)) {
        session_start();
        $_SESSION['username'] = $username;
        redirect('index.php');
    }
}

if ($connected) { ?>

    <p>Hey <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>, you're already logged in!</p>
    <a href="home.php">Home</a>
    <a href="index.php">Index</a>

    <?php } else { ?>

    <form method="POST" action="login.php">
        <label for="username">Username</label>
        <input type="text" name="username" placeholder="Username" id="username" required>

        <?php if(isset($error)){ ?>
            <p><?php echo $error; ?></p>
        <?php } ?>

        <button type="submit">Submit</button>
    </form>
    <a href="index.php">Return</a>

<?php } include('footer.php'); ?>