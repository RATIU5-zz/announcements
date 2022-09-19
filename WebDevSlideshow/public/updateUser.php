<?php 

    require_once("../private/db_connection.php");
    require_once("../private/session.php");
    require_once("../private/functions.php");
    $admin = find_admin_username();
    foreach($admin as $admin) {
        $adminUsername = $admin['username'];
        $adminID = $admin['userID'];
    }
    if(logged_in() && $_SESSION['loggedIn'] === $adminUsername) {
        // continue ::: $admin is allowed to update all users
    } elseif(logged_in()) {
        // continue ::: other users are allowed to update their own user
    } else {
        confirm_logged_in();
    }

    // check for post request
    if(isset($_POST['updateUser']) && !empty($_POST['updateUser'])) {
        // check if user is updating own user OR is chuck
        if($_SESSION['loggedIn'] === $_POST['username'] || $_SESSION['loggedIn'] === 'chuck') {

            $users = [];
            $users['userID'] = $_POST['userID'] ?? '';
            $users['newUsername'] = $_POST['newUsername'] ?? '';
            $users['password'] = $_POST['password'] ?? '';

            // apply mysqli_real_escape_string to all entries of $_POST array
            $user = array_map('mysql_prep', $users);

            // insert steps            
            $query = "UPDATE users SET 
            username = \"". $user['newUsername'] ."\", 
            password = \"". password_hash($user['password'], PASSWORD_BCRYPT) ."\"  
            where userID = ". $user['userID'];

            // run the query
            mysqli_query($db, $query);
            
            // test for mysql error and output message to users page either way
            if (mysqli_error($db) == '') {
                // if user updated their loggin ::: log them in with new username and password
                if(($_SESSION['loggedIn'] === $adminUsername && $adminID === $users['userID']) || ($_SESSION['loggedIn'] !== $adminUsername && $users['userID'] === $users['userID'])) {
                    unset($_SESSION['loggedIn']);
                    if(attempt_login($_POST['newUsername'], $_POST['password'])) {
                        // if successful store user in session
                        $_SESSION['loggedIn'] = $_POST['newUsername'];
                        setCrudMessage("User updated successfully.");
                        redirect_to('users.php');
                    } else {
                        // if not successful redirect and display error message
                        setLoginMessage("Something went wrong. Please contact your site administrator.");
                        redirect_to('login.php');
                    }
                }
                setCrudMessage("User updated successfully.");
                redirect_to("users.php");
            } else {
                // Failure on data getting into database ::: database requires unique usernames
                setCrudMessage("The user could not be updated. error: " . mysqli_error($db));
                redirect_to("users.php");
            }
        } else {
            // user tried to update different user OR wasn't chuck
            setCrudMessage("You don't have the authority to update other users.");
            redirect_to('users.php');
        }
    } else {
        // didn't receive a post request
        setCrudMessage("Please use form to update user.");
        redirect_to('users.php');
    }
?>
