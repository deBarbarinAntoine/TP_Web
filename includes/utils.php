<?php

/**
 * Redirects the user to a specified URL with an optional status code.
 *
 * @param string $url The destination URL for the redirection.
 * @param int $statusCode The HTTP status code for the redirection (default is 303).
 * @return void
 */
function redirect($url, $statusCode = 303): void
{
    header('Location: ' . $url, true, $statusCode);
    die();
}

/**
 * Guard that ensures that the user is connected. If not, redirects to the login page.
 *
 * @param bool $connected True if the user is connected, false otherwise.
 * @return void
 */
function authenticatedGuard(bool $connected): void
{
    if (!$connected) {
        $_SESSION['message'] = "You must be logged in to access this page!";
        redirect('login.php');
    }
}

/**
 * Guard that ensures that the user is not connected. If the user is connected, redirects to the home page.
 *
 * @param bool $connected True if the user is connected, false otherwise.
 * @return void
 */
function unauthenticatedGuard(bool $connected): void
{
    if ($connected) {
        $_SESSION['message'] = "You're already logged in!";
        redirect('home.php');
    }
}

/**
 * Hashes a password using the recommended Argon2ID algorithm
 * and encodes the result in Base64.
 *
 * @param string $password The plain text password to hash.
 * @return string Returns the Base64-encoded hashed password.
 */
function hashPassword(string $password): string
{
    $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
    return base64_encode($hashedPassword);
}

/**
 * Verifies a password against a given hash.
 *
 * @param string $password The plain text password.
 * @param string $hash The hashed password.
 * @return bool Returns true if the password matches the hash, false otherwise.
 */
function verifyPassword(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

/**
 * Validates if the given email address has a valid format.
 *
 * @param string $email The email address to validate.
 * @return bool Returns true if the email is valid, false otherwise.
 */
function checkEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validates if two passwords match and checks their strength.
 * Strength criteria: at least 8 characters, must include lowercase, uppercase, digits, and symbols.
 *
 * @param string $password The password to validate.
 * @param string $confirmPassword The confirmation password to match against.
 * @return bool Returns true if passwords match and are strong, false otherwise.
 */
function checkPassword(string $password, string $confirmPassword): bool
{
    if ($password !== $confirmPassword) {
        return false;
    }

    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
    return preg_match($pattern, $password) === 1;
}

/**
 * Validates if the given username is between 3 and 20 characters and safe from XSS attacks.
 *
 * @param string $username The username to validate.
 * @return bool Returns true if the username is valid and safe, false otherwise.
 */
function checkUsername(string $username): bool
{
    $sanitizedUsername = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

    if (strlen($sanitizedUsername) < 3 || strlen($sanitizedUsername) > 20) {
        return false;
    }

    return $sanitizedUsername === $username;
}

/**
 * Prints an error message in HTML from a dictionary array if the specified field exists.
 *
 * @param array $errors An associative array containing errors.
 * @param string $field The field name to check in the errors array.
 * @return void
 */
function formError(array $errors, string $field): void
{
    if (isset($errors[$field])) {
        echo '<p class="error">' . htmlspecialchars($errors[$field], ENT_QUOTES, 'UTF-8') . '</p>';
    }
}

/**
 * Prints a message from the session in HTML if it exists, then removes it from the session.
 *
 * @return void
 */
function pushMessage(): void
{
    if (isset($_SESSION['message'])) {
        echo '<p class="message">' . htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8') . '</p>';
        unset($_SESSION['message']);
    }
}

/**
 * Logs out the user by clearing the session data, destroying the session,
 * and invalidating the session cookie.
 *
 * @return void
 */
function logout(): void
{
    session_unset();
    session_destroy();
    setcookie(session_name(), "", strtotime("-1 day"));
}
