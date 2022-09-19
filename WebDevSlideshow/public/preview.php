<?php

    require_once("../private/db_connection.php");
    require_once("../private/session.php");
    require_once("../private/functions.php");
    confirm_logged_in();

    $slide = find_slide_by_id($_POST['slideID']);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Web Dev Slideshow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../public/assets/css/w3.css" rel="stylesheet" type="text/css">
    <link href="../public/assets/css/styles.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>

  <body style="width: 100vw; height:100vh;">
      <!-- background image slide -->
    <div id="slideCarousel" class="carousel slide no-scroll d-flex align-items-center justify-content-center" data-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="assets/uploads/Btech-logo.png" class="d-block w-100 img-responsive blurred" alt="...">
        </div>

        <!-- <?php //foreach($slide as $slide) { ?>

        <div class="carousel-item active">
          <img src="assets/uploads/<?php //echo $slide['imageName']; ?>" class="d-block w-100 img-responsive fix-blurred" alt="...">
        </div>

        <?php //} ?> -->

      </div>
    </div>
        
    <div id="slideshow" class="modalDialog slideModalDialog d-flex">
      <!-- Content for modal -->
      <div id="slideCarousel" class="carousel slide d-flex align-items-center justify-content-center" data-ride="carousel">
        <div class="carousel-inner d-flex align-items-center">
          <div class="carousel-item active height-limiter d-flex justify-content-center">
            <img src="assets/uploads/Btech-logo.png" class="d-block w-0 mw-0 h-0 mh-0 image-fit" alt="...">
          </div>

          <?php foreach($slide as $slide) { ?>

          <div class="carousel-item active height-limiter d-flex justify-content-center">
            <img src="assets/uploads/<?php echo $slide['imageName']; // $slide['imageName'] becomes an illegal string offset fixed when top foreach is commented out?>" class="d-block w-100 mw-100 h-auto mh-100 image-fit" alt="...">
          </div>
              
          <?php } ?>

        </div>
      </div>
  
      <div style="position:fixed; bottom: 25px; right: 25px; width: 100px; height: 100px; border-radius: 50%; margin: 0;"><a href="slides.php" style="position:fixed; bottom: 25px; right: 25px; width: 100px; height: 100px; border-radius: 50%; margin: 0;"><i class="fa fa-chevron-left" aria-hidden="true" style="font-size: 3em; margin-top: 30px; margin-left: 30px;"></i></a></div>

    </div><!-- end openModal -->

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('#slideshow').modal('show');
        });
    </script>
  </body>
</html>