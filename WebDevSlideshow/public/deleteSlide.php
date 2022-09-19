<?php 

    require_once("../private/db_connection.php");
    require_once("../private/session.php");
    require_once("../private/functions.php");
    confirm_logged_in();

    $slideID = filter_input(INPUT_POST, 'slideID', FILTER_VALIDATE_INT);
    $imageName = filter_input(INPUT_POST, 'imageName');
    $oldPosition = filter_input(INPUT_POST, 'oldPosition', FILTER_VALIDATE_INT);

    // use data from the delete form to prime the following database operation

    if(isset($_POST['deleteSlide']) && !empty($_POST['deleteSlide'])) {

        // get data from slide delete form (slideID and imageName)

        shift_slide_positions($oldPosition, 0, $slideID);

        $query = "DELETE FROM slides WHERE slideID ='" . $slideID . "' " . "LIMIT 1";
        mysqli_query($db, $query);

        if(mysqli_error($db)) {
            // Failure
            setCrudMessage("Slide deletion failed. error message: " . mysqli_error());
            redirect_to("slides.php");
        } else {
            // if data successfully removed from database, delete image from uploads folder
            unlink("assets/uploads/" . $imageName);

            // Success
            setCrudMessage("Slide deleted successfully.");
            redirect_to("slides.php");
        }
    } else {
        setCrudMessage("Delete unsuccessful. Please contact your site administrator.");
        redirect_to("slides.php");
    }

?>
