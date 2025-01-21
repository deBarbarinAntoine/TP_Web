<?php
// Login page for user authentication and session handling

include_once('models/user.php');
use models\User;

// Set page variables
$pageTitle = 'Login';
$errors = [];

// Utility functions such as redirection and password handling
include_once('includes/utils.php');

// Session management to check user connection status
include_once('includes/sessions.php');

// Header of the webpage with common elements
include_once('includes/header.php');

unauthenticatedGuard($isConnected);

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // DEBUG
    print($email . ' tried to login.');

    if (!checkEmail($email)) {
        $errors['email'] = "Invalid email format!";
    }

    if (empty($errors)) {

        $user = User::login($email, $password);

        if (isset($user)) {

            // DEBUG
            print('login successful');

            logout();
            session_start();

            $_SESSION['id'] = $user->getId();
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $user->getUsername();

            redirect('index.php');
        } else {

            // DEBUG
            print('a problem occurred');
        }

        $errors['credentials'] = "Invalid email or password!";
    }
}
?>

<form method="POST" action="login.php">
    <label for="email">Email</label>
    <input type="email" name="email" placeholder="Email" id="email" required autofocus>
    <?php formError($errors, 'email'); ?>

    <label for="password">Password</label>
    <input type="password" name="password" placeholder="Password" id="password" required>
    <?php formError($errors, 'credentials'); ?>

    <button type="submit">Login</button>
</form>

<p>You don't have an account yet?</p>
<a href="/register.php">Register</a>

<a href="index.php">Return</a>

<?php include('includes/footer.php'); ?>