<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>ADD CATEGORY</h1>
        <br><br>

        <?php 
        
            if(isset($_SESSION['add']))
            {
                echo $_SESSION['add'];
                unset($_SESSION['add']);
            }

            if(isset($_SESSION['upload']))
            {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }
        
        ?>

        <br><br>

        <!--Add Category Starts Here -->
        <form action="" method="POST" enctype="multipart/form-data">

            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="title" placeholder="Category Title">
                    </td>
                </tr>

                <tr>
                    <td>Select Image:</td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Featured:</td>
                    <td>
                        <input type="radio" name="featured" value="Yes"> Yes
                        <input type="radio" name="featured" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes"> Yes
                        <input type="radio" name="active" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Category" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>
        <!--Add Category Ends Here -->

        <?php 
        
            // Check If Button is Clicked Or Not
            if(isset($_POST['submit']))
            {
                // 1. Get The value from category form
                $title = $_POST['title'];

                // For Radio Input Type We Need To Check the Whether the Button is Clicked Or Not
                if(isset($_POST['featured']))
                {
                    // Get the value
                    $featured = $_POST['featured'];
                }

                else
                {
                    // Set Default Value
                    $featured = "No";
                }

                if(isset($_POST['active']))
                {
                    $active = $_POST['active'];
                }

                else
                {
                    $active = "No";
                }

                // Check Whether the image is selected or not
                // print_r($_FILES['image']);

                // die(); // break the code here

                if(isset($_FILES['image']['name']))
                {
                    // Upload Image
                    // To Upload we need image name , source path and destination path
                    $image_name = $_FILES['image']['name'];
                    
                    // Upload the Image only if available
                    if($image_name != "")
                    {
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
                            header('location:'.SITEURL.'admin/add-category.php');
                            // Stop The Process
                            die();
                        }

                    }          
                }

                else
                {
                    // Don't Upload Image and set image value blank
                    $image_name = "";
                }

                // 2. Create SQL Query
                $sql = "INSERT INTO tbl_category SET
                        title = '$title',
                        image_name='$image_name',
                        featured = '$featured',
                        active = '$active'
                ";

                // 3. Execute Query
                $res = mysqli_query($conn , $sql);

                // 4. Check Whether Query Executed Or Not

                if($res == TRUE)
                {
                    // Query Executed and Category Added
                    $_SESSION['add'] = "<div class='success'>Category Added Successfully.</div>";
                    // Redirect To Manage Category Page
                    header('location:'.SITEURL.'admin/manage-category.php');
                }

                else
                {
                    // Failed To Add Category
                    $_SESSION['add'] = "<div class='error'>Failed To Add Category.</div>";
                    // Redirect To Manage Category Page
                    header('location:'.SITEURL.'admin/add-category.php');
                }
            }
        ?>

    </div>
</div>


<?php include('partials/footer.php'); ?>