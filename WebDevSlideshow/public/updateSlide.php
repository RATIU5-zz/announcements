<?php 

    require_once("../private/db_connection.php");
    require_once("../private/session.php");
    require_once("../private/functions.php");
    confirm_logged_in();

    // if there is an image to upload (update with), process it
    if(isset($_FILES["fileToUpload"]["name"]) && !empty($_FILES["fileToUpload"]["name"])) {

        $hasNewImage = true;
        $target_dir = "assets/uploads/";
        $imageName = basename($_FILES["fileToUpload"]["name"]);
        $target_file = $target_dir . $imageName;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        $tempFile = $_FILES["fileToUpload"]["tmp_name"];

        $uploadOk = 1;

        $error = [];
        
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            $error[] = "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $error[] = "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $error[] = "file already exists";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 50000000) {
            $error = "file too large";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $error[] = "file type: $imageFileType not supported";
            $uploadOk = 0;
        }

        // if the image is ok...
        if ($uploadOk) {

            // if image is good to be updated, delete old image from uploads folder
            unlink("assets/uploads/{$_POST['originalImage']}");

            // attempt to upload new image
            if(move_uploaded_file($tempFile, $target_file)) {

                $slides = [];
                $slides['slideID'] = $_POST['slideID'] ?? '';
                $slides['position'] = $_POST['position'] ?? '';
                $slides['imageName'] = $_FILES['fileToUpload']['name'] ?? '';
                $slides['oldPosition'] = $_POST['oldPosition'] ?? '';

                // apply mysqli_real_escape_string to all entries of $_POST array
                $slide = array_map('mysql_prep', $slides);

                shift_slide_positions($slides['oldPosition'], $slides['position'], $slides['slideID']);

                //* insert steps for attempting image update               
                $query = "UPDATE slides SET 
                position = \"". $slide['position'] ."\",
                imageName = \"". $slide['imageName'] ."\"
                where slideID = ". $slide['slideID'];

                // run the query
                mysqli_query($db, $query);
                
                // test for mysql error and output message to slides page either way
                if (mysqli_error($db) == '') {
                    // Success on data getting into database
                    setCrudMessage("Slide updated successfully.");
                    redirect_to("slides.php");
                } else {
                    // if data was NOT successfully updated while image update attempted, delete image from uploads folder
                    unlink("assets/uploads/$imageName");

                    // Failure on data getting into database
                    setCrudMessage("The slide could not be updated. error: " . mysqli_error($db));
                    redirect_to("slides.php");
                }
            }
        } else {
            // Failure on image checks
            setCrudMessage("The slide could not be updated. There was an issue with the image file you were trying to upload.");
            redirect_to("slides.php");
        }
    } else { // if not updating the image

        $slides = [];
        $slides['slideID'] = $_POST['slideID'] ?? '';
        $slides['position'] = $_POST['position'] ?? '';
        $slides['oldPosition'] = $_POST['oldPosition'] ?? '';

        // apply mysqli_real_escape_string to all entries of $_POST array
        $slide = array_map('mysql_prep', $slides);

        shift_slide_positions($slides['oldPosition'], $slides['position'], $slides['slideID']);

        // insert steps without attempting image update               
        $query = "UPDATE slides SET 
        position = \"". $slide['position'] ."\" 
        where slideID = ". $slide['slideID'];

        // run the query
        mysqli_query($db, $query);
        
        // test for mysql error and output message to slides page either way
        if (mysqli_error($db) == '') {
            // Success on data getting into database
            setCrudMessage("Slide updated successfully.");
            redirect_to("slides.php");
        } else {
            // Failure on data getting into database
            setCrudMessage("The slide could not be updated. error: " . mysqli_error($db));
            redirect_to("slides.php");
        }

    }
?>
