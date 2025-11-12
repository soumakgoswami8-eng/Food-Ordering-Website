<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Category</h1>

        <br><br>

        <?php 
            
            // Check Id Is Set Or Not
            if(isset($_GET['id']))
            {
                // Get the id and other details
                //echo "Getting The Data";
                $id = $_GET['id'];

                // SQL Query
                $sql = "SELECT * FROM tbl_category WHERE id = $id";

                // Execute
                $res = mysqli_query($conn , $sql);

                // Count Rows
                $count = mysqli_num_rows($res);

                if($count == 1)
                {
                    // Get All Data
                    $row = mysqli_fetch_assoc($res);
                    $title = $row['title'];
                    $current_image = $row['image_name'];
                    $featured = $row['featured'];
                    $active = $row['active'];
                }

                else
                {
                    // Redirect with message
                    $_SESSION['no-category-found'] = "<div class='error'>Category Not Found</div>";
                    header('location:'.SITEURL.'admin/manage-category.php');
                }
            }

            else
            {
                // Redirect
                header('location:'.SITEURL.'admin/manage-category.php');
            }
        
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="title" value="<?php echo $title; ?>">
                    </td>
                </tr>

                <tr>
                    <td>Current Image:</td>
                    <td>
                        <?php 
                            if($current_image != "")
                            {
                                // Display Image
                                ?>
                                <img src="<?php echo SITEURL; ?>images/category/<?php echo $current_image; ?>" width="150px">
                                <?php
                            }

                            else
                            {
                                // Display Message
                                echo "<div class='error'>Image Not Added</div>";
                            }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>New Image: </td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Featured:</td>
                    <td>
                        <input <?php if($featured == "Yes"){echo "checked";} ?> type="radio" name="featured" value="Yes">Yes

                        <input <?php if($featured == "No"){echo "checked";} ?> type="radio" name="featured" value="No">No
                    </td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input <?php if($active == "Yes"){echo "checked";} ?> type="radio" name="active" value="Yes">Yes

                        <input <?php if($active == "No"){echo "checked";} ?> type="radio" name="active" value="No">No
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Update Category" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>

        <?php 
        
            if(isset($_POST['submit']))
            {
                //echo "Clicked";
                // Get all values from form
                $id = $_POST['id'];
                $title = $_POST['title'];
                $current_image = $_POST['current_image'];

                $featured = $_POST['featured'];
                $active = $_POST['active'];

                // Updating New Image if Selected

                // Check Image Is Selected Or Not
                if(isset($_FILES['image']['name']))
                {
                    // Get the Image Details
                    $image_name = $_FILES['image']['name'];

                    // Check Whether The Image Is Available Or Not
                    if($image_name != "")
                    {
                        // A.Upload New Image  
                        // Auto Rename Our Image
                        // Get The Extension of Our Image(.jpg , png , etc)
                        $ext = end(explode('.',$image_name));

                        //Rename The Image
                        $image_name = "Food_Category_".rand(000,999).'.'.$ext;  // e.g., Food_Category_834.jpg

                        $source_path = $_FILES['image']['tmp_name'];

                        $destination_path = "../images/category/".$image_name;

                        // Finally Upload Image
                        $upload = move_uploaded_file($source_path,$destination_path);

                        // Check whether the image is uploaded or not..
                        // And If The Image Is Not Uploaded then we will stop the process and redirect
                        if($upload == FALSE)
                        {
                            $_SESSION['upload'] = "<div class='error'>Failed To Upload Image</div>";
                            // Redirect
                            header('location:'.SITEURL.'admin/manage-category.php');
                            // Stop The Process
                            die();
                        }


                        // B. Remove Current Image If Available
                        if($current_image != "")
                        {
                            $remove_path = "../images/category/".$current_image;
                            $remove = unlink($remove_path);

                            // Check The Image Is Removed Or Not
                            // If Failed To Removed Then Display Message
                            if($remove == false)
                            {
                                $_SESSION['failed-remove'] = "<div class='error'>Failed To Remove Current Image</div>";
                                header('location:'.SITEURL.'admin/manage-category.php');
                                die();  // Stop Process
                            }
                        }

                    }

                    else
                    {
                        $image_name = $current_image;
                    }
                }

                else
                {
                    $image_name = $current_image;
                }

                // Update The Database
                $sql2 = "UPDATE tbl_category SET
                        title = '$title',
                        image_name = '$image_name',
                        featured = '$featured',
                        active = '$active'
                        WHERE id = $id
                ";

                // Execute
                $res2 = mysqli_query($conn , $sql2);
                
                // Redirect To Manage Category
                // Check Query Executed
                if($res2 == TRUE)
                {
                    $_SESSION['update'] = "<div class='success'>Category Updated Successfully</div>";
                    header('location:'.SITEURL.'admin/manage-category.php');
                }

                else
                {
                    $_SESSION['update'] = "<div class='error'>Category Updation Failed</div>";
                    header('location:'.SITEURL.'admin/manage-category.php');
                }
            }                    
        
        ?>

    </div>
</div>

<?php include('partials/footer.php'); ?>