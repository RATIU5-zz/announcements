<?php

    require_once("../private/db_connection.php");
    require_once("../private/session.php");
    require_once("../private/functions.php");
    confirm_logged_in();

    include("../private/layout/header.php");

    $slides = find_all_slides();
    $slides_count = mysqli_num_rows($slides);
    $time = find_time();

?>

<body class="bg-light">

    <section class="section">
        <h1 class="section-header uncenter">Slides</h1>
    </section>

    <div class="slides-box min-vh-100">
        <div class="container d-flex justify-content-center">
            <?php echo message("crudMessage"); ?>
        </div>

        <section class="section">
            <div class="container d-flex justify-content-between mb-1">
                <a href="#addSlideModal" class='btn btn-dark' role="button">+Add A New Slide</a>
                <a href="#setSlideTimeModal" class='btn btn-dark' role="button">Set Slide Duration</a>
            </div>

            <table class="slides-table">
                <tr>
                    <th>Image</th>
                    <th>Position</th>
                </tr>

                <?php foreach($slides as $slide) { ?>
                
                <tr>
                    <td><img src="assets/uploads/<?php echo $slide['imageName']; ?>" alt=""></td>
                    <td><?php echo $slide['position']; ?></td>
                    <td class="table-button-box">
                        <form class="table-button" action="preview.php#slideshow" method="post">
                            <input type="hidden" name="slideID" value="<?php echo $slide['slideID']; ?>">
                            <input class="btn btn-dark" type="submit" name="preview" value="View">
                        </form>
                    </td>

                    <td class="table-button-box table-button-box-right">
                        <form class="table-button" action="#editSlideModal" method="post">
                            <!-- pass slideID to prepopulate modal with current data -->
                            <input type="hidden" name="slideID" value="<?php echo $slide['slideID']; ?>">
                            <input class="btn btn-dark" type="submit" name="edit" value="Edit">
                        </form>
                    </td>
                    
                    <td class="table-button-box table-button-box-right">
                        <form class="table-button" action="#deleteSlideModal" method="post">
                            <!-- pass slideID and imageName to delete database record and file upload -->
                            <input type="hidden" name="slideID" value="<?php echo $slide['slideID']; ?>">
                            <input type="hidden" name="imageName" value="<?php echo $slide['imageName']; ?>">
                            <input class="btn btn-dark" type="submit" name="delete" value="Delete">
                        </form>
                    </td>
                </tr>

                <?php } ?>

            </table>

            <div style="margin: 25px 0px;">
                <a class="btn btn-dark" href="#addSlideModal" role="button">+Add A New Slide</a>
            </div>

            <div id="addSlideModal" class="modalDialog slideModalDialog">
                <div><a href="#close" title="Close" class="close">X</a>
                    <!-- Content for modal -->
                    <div class="modal-header border-bottom-0 p-3">
                        <h2>Add Slide</h2>
                    </div>
                    <div class="login Slide mt-0">
                        <?php echo message("crudMessage"); ?>
                        <form action="addSlide.php"  method="POST" enctype="multipart/form-data">
                            <label for="position">Position</label>
                            <input type="number" name="position" id="position" min="1" max="<?php echo $slides_count + 1; ?>" value="<?php echo $slides_count + 1; ?>">
                            <label for="fileToUpload">Slide Image</label>
                            <input type="file" name="fileToUpload" id="fileToUpload" >
                            <input class="btn btn-primary" role="button" type="submit" name="addSlide" value="Add Slide">
                        </form>
                    </div><!-- end login Slide -->
                </div><!-- end close -->
            </div><!-- end openModal -->

            <div id="editSlideModal" class="modalDialog slideModalDialog">
                <?php
                if(isset($_POST['slideID']) && !empty($_POST['slideID'])) {

                        $slide = find_slide_by_id($_POST['slideID']);
                    ?>
                    <div><a href="#close" title="Close" class="close">X</a>
                        <!-- Content for modal -->
                        <div class="modal-header border-bottom-0 p-3">
                            <h2>Edit Slide</h2>
                        </div>
                        <div class="login Slide scroll mt-0">
                            <?php echo message("crudMessage"); ?>

                            <?php foreach ($slide as $slide) { ?>

                            <form action="updateSlide.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="slideID" value="<?php echo $slide['slideID']; ?>">
                                <label for="position">Position</label>
                                <input type="number" name="position" id="position" min="1" max="<?php echo $slides_count; ?>" value="<?php echo $slide['position']; ?>">
                                <input type="hidden" name="oldPosition" value="<?php echo $slide['position']; ?>">
                                <label for="fileToUpload">Slide Image</label><br>
                                <img class="editImage" src="assets/uploads/<?php echo $slide['imageName']; ?>" alt="Slide">
                                <input type="hidden" name="originalImage" value="<?php echo $slide['imageName']; ?>" >
                                <input type="file" name="fileToUpload" id="fileToUpload" >
                                <input class="btn btn-primary" role="button" type="submit" name="updateSlide" value="Save Changes">
                            </form>

                            <?php } ?>

                            <a class="btn btn-secondary w-100 mt-1" href="slides.php">Cancel</a>

                        </div><!-- end login Slide -->
                    </div><!-- end close -->
                <?php } ?>
            </div><!-- end openModal -->

            <div id="deleteSlideModal" class="modalDialog slideModalDialog">
                <?php
                if(isset($_POST['slideID']) && !empty($_POST['slideID'])) {

                        $slide = find_slide_by_id($_POST['slideID']);
                    ?>
                    <div><a href="#close" title="Close" class="close">X</a>
                        <!-- Content for modal -->
                        <div class="modal-header p-3">
                            <h2>Delete Slide</h2>
                        </div>
                        <div class="login Slide scroll" style="margin-top: 0px;">
                            <h4>Are you sure you want to DELETE this slide?</h4>
                            <?php echo message("crudMessage"); ?>

                            <?php foreach ($slide as $slide) { ?>

                            <form action="deleteSlide.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="slideID" value="<?php echo $slide['slideID']; ?>">
                                <input type="hidden" name="oldPosition" value="<?php echo $slide['position']; ?>">
                                <label for="fileToUpload">Slide Image</label><br>
                                <img class="editImage" style="max-width: 250px;" src="assets/uploads/<?php echo $slide['imageName']; ?>" alt="Slide">
                                <input type="hidden" name="imageName" value="<?php echo $slide['imageName']; ?>" >
                                <input class="btn btn-danger mt-2" role="button" type="submit" name="deleteSlide" value="DELETE">
                            </form>

                            <?php } ?>

                            <a class="btn btn-secondary w-100 mt-1" href="slides.php">Cancel</a>

                        </div><!-- end login Slide -->
                    </div><!-- end close -->
                <?php } ?>
            </div><!-- end openModal -->

            <div id="setSlideTimeModal" class="modalDialog slideModalDialog">
                <div><a href="#close" title="Close" class="close">X</a>
                    <!-- Content for modal -->
                    <div class="modal-header border-bottom-0">
                        <h2>Change Slide Duration</h2>
                    </div>
                    <div class="login Slide mt-0">
                        <?php echo message("crudMessage"); ?>

                        <?php foreach($time as $time) { ?>

                        <form action="setSlideTime.php"  method="POST" enctype="multipart/form-data">
                            <label for="duration">Duration In Seconds</label>
                            <input type="number" name="duration" id="duration" min="1" max="60" value="<?php echo $time['duration']; ?>">
                            <input type="hidden" name="timeID" value="<?php echo $time['timeID']; ?>" >
                            <input class="btn btn-primary" role="button" type="submit" name="setTime" value="Set Duration">
                        </form>

                        <?php } ?>

                    </div><!-- end login Slide -->
                </div><!-- end close -->
            </div><!-- end openModal -->
        </section><!-- end Slide section-->
    </div>

    <?php include("../private/layout/footer.php"); ?>

</body>

