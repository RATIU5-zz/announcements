<?php 

    require_once("../private/db_connection.php");
    require_once("../private/session.php");
    require_once("../private/functions.php");
    confirm_logged_in();

    // did we get a file to upload?
    if (isset($_POST['addSlide'])) {

        $target_dir = "assets/uploads/";
        $imageName = basename($_FILES["fileToUpload"]["name"]);
        $target_file = $target_dir . $imageName;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        $tempFile = $_FILES["fileToUpload"]["tmp_name"];

        $uploadOk = 1;

        $error = [];
    
        // Check if image file is a actual image or fake image
        if(isset($_POST["addSlide"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                $error[] = "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $error[] = "File is not an image.";
                $uploadOk = 0;
            }
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
            // try to upload it
            if(move_uploaded_file($tempFile, $target_file)) { 
                // if image upload successful, attempt database operations
                // uses data from the form to make the following database operations work
                $slides = [];
                $slides['position'] = $_POST['position'] ?? '';
                $slides['imageName'] = $_FILES['fileToUpload']['name'] ?? '';

                $slide = array_map('mysql_prep', $slides);
                
                shift_slide_positions(0, $slides['position']);

                //* insert steps                
                $query = "INSERT INTO slides 
                (position, imageName) 
                values
                (\"". $slide['position'] ."\",  
                \"". $slide['imageName'] ."\")";

                // run the query
                mysqli_query($db, $query);
                
                // test for mysql error and output message to slide page either way
                // if database query fails, delete image file from the uploads folder
                if (mysqli_error($db) == '') {
                    // Success on data getting into database
                    setCrudMessage("Slide added successfully.");
                    redirect_to('slides.php');
                } else {
                    // Failure on data getting into database
                    setCrudMessage("We could not add the slide. Something is wrong with the submitted information.");

                    // if data was NOT successfully inserted into database, delete image from uploads folder
                    unlink("assets/uploads/$imageName");
                    redirect_to('slides.php');
                }
            } else {
                // Failure on upload function
                setCrudMessage("We could not add the slide. Something went wrong with the upload.");
                redirect_to('slides.php');
            }
        } else { // failure to pass image checks
            setCrudMessage("We could not add the slide. Something is wrong with the image you tried to upload.");
            redirect_to('slides.php');
        }
    } else {
        // if no file was given
        setCrudMessage("Please add a slide");
        redirect_to('slides.php');
    }
?>