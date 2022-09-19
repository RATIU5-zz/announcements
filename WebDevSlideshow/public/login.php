<?php

    require_once("../private/db_connection.php");
    require_once("../private/functions.php");
    require_once("../private/session.php");

    include("../private/layout/header.php");
    
    // if already logged in go directly to slides.php
    if(logged_in()) {
        redirect_to('slides.php');
    }

    // if user tried to log in
    if(is_post_request()) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        // check if successful
        if(attempt_login($username, $password)) {
        // if successful store user in session
        $_SESSION['loggedIn'] = $username;
        redirect_to('slides.php');
        } else {
        // if not successful redirect and display error message
        setLoginMessage("Username or password is incorrect.");
        redirect_to('login.php');
        }
    }

?>

    <div class="container login min-vh-100">
        <h2>Sign in</h2>
        <?php echo message("loginMessage"); ?>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <input class="btn btn-primary" type="submit" name="login" value="Login">
        </form>
    </div>

<?php include("../private/layout/footer.php"); ?>