<?php
function redirect($url, $statusCode = 303): Void
{
    header('Location: ' . $url, true, $statusCode);
    die();
}