<?php

require_once ('../models/user.php');
    use models\user;

// Session management to check user connection status
include_once('../includes/sessions.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["email"])) {
        header('Content-Type: application/json');

        $exists = User::exists($_GET["email"]);

        $response =  [ 'response' => $exists ];

        echo json_encode($response);
        die();
    }
    print "<script>console.log('A problem occurred!');</script>)";
    die();
}