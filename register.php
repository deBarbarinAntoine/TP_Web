<?php
// This script renders the user registration page, handles form submission to validate user data,
// and facilitates user account creation if the inputs are valid.

include_once('models/user.php');
use models\User;

// Set page variables
$pageTitle = 'Register';
$errors = [];

// Include utility functions for operations like redirection and password validation
include_once('includes/utils.php');

// Include session management to identify if the user is already logged in
include_once('includes/sessions.php');

// Include the webpage header, which contains common styling and navigation elements
include_once('includes/header.php');

// If the user is already logged in, redirect them away from the registration page
unauthenticatedGuard($isConnected);

// Handle the form submission to collect and validate user data for account creation
if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // DEBUG
    print($username . ' tried to create an account.');

    // Validate the username and add an error message if it is invalid
    if (!checkUsername($username)) {
        $errors['username'] = 'Invalid username';
    }
    // Validate the email address and add an error message if it is invalid
    if (!checkEmail($email)) {
        $errors['email'] = 'Invalid email';
    }
    // Validate the password and confirmation password, ensuring they match and follow security rules
    if (!checkPassword($password, $confirmPassword)) {
        $errors['password'] = 'Invalid password';
    }

    if (empty($errors)) {
        print($username . ' created.');
        // Attempt to create a new user account. If successful, redirect to the login page.
        $user = User::new($username, $email, $password);
        if (isset($user)) {
            $_SESSION['message'] = 'Your account has been created!';
            redirect('/login.php');
        }
    }
}
?>

<form action="/register.php" method="post">
    <!-- Input for the username: validates and displays an error message if invalid -->
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" placeholder="Username" required autofocus>
    <div class="errors" id="username-error">
        <?php formError($errors, 'username'); ?>
    </div>

    <!-- Input for the email: validates and displays an error message if invalid -->
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" placeholder="Email" onkeyup="checkEmail(this.value)" required>
    <div class="errors" id="email-error">
        <?php formError($errors, 'email'); ?>
    </div>

    <!-- Input for the password: requires a secure password/matching confirm-password and displays an error message if invalid -->
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" placeholder="Password" required>
    <div class="errors" id="password-error">
        <?php formError($errors, 'password'); ?>
    </div>

    <label for="confirm-password">Confirm Password:</label>
    <input type="password" name="confirm-password" id="confirm-password" required>

    <button type="submit">Register</button>
</form>

<p>Already have an account?</p>
<a href="/login.php">Login</a>

<!-- Link to return to the homepage -->
<a href="/index.php">Return</a>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const emailErrorCtn = document.querySelector('#email-error');

    function validateEmail(email) {
        return String(email)
            .toLowerCase()
            .match(
                /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            );
    }

    function checkEmail(email) {
        if (validateEmail(email)) {
            axios.get('/ajax/user.php', {
                params: {
                    email: email,
                }
            })
                .then(response => {
                    let message;
                    // console.log(`response: ${JSON.stringify(response.data.response)}`);

                    if (response.data.response) {
                        message = 'email unavailable';
                    } else {
                        message = 'email available';
                    }
                    emailErrorCtn.innerHTML = `<p>${message}</p>`;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
</script>

<?php

include('includes/footer.php');

?>
