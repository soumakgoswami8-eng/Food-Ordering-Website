<?php

    include('../config/constants.php');

    //echo "Delete Food Section";

    if(isset($_GET['id']) AND isset($_GET['image_name']))
    {
        // Process To Delete
        //echo "Process To Delete";

        // 1. Get Id And Image
        $id = $_GET['id'];
        $image_name = $_GET['image_name'];

        // 2. Remove Available Image
        // Check Image Availability and Then Delete

        if($image_name != "")
        {
            // Image Is Present and Need To Remove From Photos
            $path = "../images/food/".$image_name;

            // Remove Image File
            $remove = unlink($path);

            // Check Image Is Removed Or Not?
            if($remove == FALSE)
            {
                // Failed To Remove Image
                $_SESSION['upload'] = "<div class='error'>Failed To Remove Image</div>";
                // Redirect
                header('location:'.SITEURL.'admin/manage-food.php');
                // Stop
                die();
            }
        }

        // 3. Delete Food From Database

        $sql = "DELETE FROM tbl_food WHERE id = $id";

        // Execute
        $res = mysqli_query($conn , $sql);

        // 4. Redirect
        // Check Whether Query Executed
        if($res == TRUE)
        {
            // Deletion
            $_SESSION['delete'] = "<div class='success'>Food Deleted Successfully</div>";
            header('location:'.SITEURL.'admin/manage-food.php');
        }

        else
        {
            // Failed
            $_SESSION['delete'] = "<div class='error'>Food Deletion Failed</div>";
            header('location:'.SITEURL.'admin/manage-food.php');
        }

    }

    else
    {
        // Redirect
        //echo "Redirect";
        $_SESSION['unauthorize'] = "<div class='error'>Unauthorised Access</div>";
        header('location:'.SITEURL.'admin/manage-food.php');
    }

?>