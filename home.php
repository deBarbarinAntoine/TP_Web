<?php
include('utils.php');
$pageTitle = 'Home';
include('sessions.php');
include "header.php";

if ($connected) { ?>

        <p>Hey, still there <?php echo $username; ?>?</p>
        <a href="index.php">Return</a>

    <?php

} else {
    redirect('index.php');
}
include "footer.php";

?>