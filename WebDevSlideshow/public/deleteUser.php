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
        // continue ::: $admin is allowed to delete all users
    } elseif(logged_in()) {
        // users can't delete any users
        setCrudMessage("You don't have the authority to delete users");
        redirect_to('users.php');
    } else {
        confirm_logged_in();
    }

    $userID = filter_input(INPUT_POST, 'userID', FILTER_VALIDATE_INT);
    if($userID == $adminID) {
        // admin can't be deleted
        redirect_to('users.php');
    }

    // uses data from the delete form to prime the following database operation

    if(isset($_POST['deleteUser']) && !empty($_POST['deleteUser'])) {

        // get data from slide delete form (slideID and imageName)

        $query = "DELETE FROM users WHERE userID ='" . $userID . "' " . "LIMIT 1";
        mysqli_query($db, $query);

        if(mysqli_error($db)) {
            // Failure
            setCrudMessage("User deletion failed. error message: " . mysqli_error());
            redirect_to("users.php");
        } else {
            // Success
            setCrudMessage("User deleted successfully.");
            redirect_to("users.php");
        }
    } else {
        setCrudMessage("Delete unsuccessful. Please contact your site administrator.");
        redirect_to("Users.php");

    }

?>
