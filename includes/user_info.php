<?php

require_once('models/user.php');
use models\user;

try {
    $user = User::get($_SESSION['id']);

    echo "<p>Id: " . $user->getId() . "</p>";
    echo "<p>Username: " . $user->getUsername() . "</p>";
    echo "<p>Email: " . $user->getEmail() . "</p>";
    echo "<img src='" . $user->getAvatar() . "' alt='Avatar'>";
} catch (Exception $e) {
    // TODO -> implement logging
    print $e->getMessage();
}


