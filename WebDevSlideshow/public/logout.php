<?php
    require_once("../private/session.php");
    require_once("../private/functions.php");

    // log out and redirect to login page.
    unset($_SESSION['loggedIn']);
    redirect_to('login.php');
?>