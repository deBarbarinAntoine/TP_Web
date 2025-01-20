<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$connected = (isset($_SESSION['username']) && !empty($_SESSION['username']));

if ($connected) {
    $username = $_SESSION['username'];
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
}

?>