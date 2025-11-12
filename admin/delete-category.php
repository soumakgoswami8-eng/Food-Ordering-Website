<?php 

    // Include Constants file
    include('../config/constants.php');

    //echo "Delete Page";
    // Check Whether The ID and image_name is set or not
    if(isset($_GET['id']) AND isset($_GET['image_name']))
    {
        // Get The Value And Delete
        // echo "Get Value and Delete";
        $id = $_GET['id'];
        $image_name = $_GET['image_name'];

        // Remove The Physical Image File if available
        if($image_name != "")
        {
            // image available , so remove it
            $path = "../images/category/".$image_name;
            // Remove The Image
            $remove = unlink($path);

            // If Failed To Remove Image then add an error message
            if($remove == FALSE)
            {
                // Set The Session Message
                $_SESSION['remove'] = "<div class='error'>Failed To Remove Image.</div>";
                // Redirect
                header('location:'.SITEURL.'admin/manage-category.php');
                // Stop The Process
                die();
            }
        }


        // Delete Data from Database
        // SQL Query Delete
        $sql = "DELETE FROM tbl_category WHERE id=$id";

        // Execute
        $res = mysqli_query($conn , $sql);

        // Check Whether Data is Deleted From Database
        if($res == TRUE)
        {
            // SET success and redirect
            $_SESSION['delete'] = "<div class='success'> Category Deleted Successfully</div>";
            header('location:'.SITEURL.'admin/manage-category.php');
        }

        else
        {
            // SET fail and redirect
            $_SESSION['delete'] = "<div class='error'>Failed To Delete Category</div>";
            header('location:'.SITEURL.'admin/manage-category.php');
        }

    }

    else
    {
        // Redirect To Manage Category Page
        header('location:'.SITEURL.'admin/manage-category.php');
    }

?>