<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Food</h1>

        <br><br>

        <?php 
        
            if(isset($_SESSION['upload']))
            {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }
        
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">

                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="title" placeholder="Title Of The Food">
                    </td>
                </tr>

                <tr>
                    <td>Description:</td>
                    <td>
                        <textarea name="description" cols="30" rows="5" placeholder="Description Of The Food"></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Price:</td>
                    <td>
                        <input type="number" step="0.01" name="price">
                    </td>
                </tr>

                <tr>
                    <td>Select Image:</td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Category:</td>
                    <td>
                        <select name="category">

                            <?php 
                                // Create PHP code To display categories from database

                                // 1.Create SQL Query
                                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                // Execute
                                $res = mysqli_query($conn , $sql);

                                // Count Rows To Check whether we have categories or not
                                $count = mysqli_num_rows($res);

                                // If COunt > 0 We have Categories , else do not have categories
                                if($count>0)
                                {
                                    // Categories
                                    while($row=mysqli_fetch_assoc($res))
                                    {
                                        // Get The Details
                                        $id = $row['id'];
                                        $title = $row['title'];
                                        ?>

                                        <Option value="<?php echo $id;?>"><?php echo $title;?></Option>

                                        <?php
                                    }
                                }

                                else{
                                    // Do Not Have Categories
                                    ?>

                                    <Option value="0">No Category Found</Option>
                                    
                                    <?php
                                }
                            ?>

                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Featured:</td>
                    <td>
                        <input type="radio" name="featured" value="Yes">Yes
                        <input type="radio" name="featured" value="No">No
                    </td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes">Yes
                        <input type="radio" name="active" value="No">No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Food" class = btn-secondary>
                    </td>
                </tr>

            </table>
        </form>

        <?php 
        
            // Check Button Clicked
            if(isset($_POST['submit']))
            {
                //echo "Clicked";

                // 1. Get The Data From Form
                $title = $_POST['title'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $category = $_POST['category'];

                // Check Whether Radio Button for featured and active is checked or not
                if(isset($_POST['featured']))
                {
                    $featured = $_POST['featured'];
                }

                else
                {
                    $featured = "No";       // Default
                }

                if(isset($_POST['active']))
                {
                    $active = $_POST['active'];
                }

                else
                {
                    $active = "No";     // Default
                }

                // 2. Upload Image

                // Check Image Is Selected Or Not
                if(isset($_FILES['image']['name']))
                {
                    // Get The Details Of The Selected
                    $image_name = $_FILES['image']['name'];

                    // Check Image Is Selected Or Not and Upload Only When Available
                    if($image_name != "")
                    {
                        // Image Selected
                        // A. Rename The Image 
                        $ext = end(explode('.',$image_name));

                        // Create New Name
                        $image_name = "Food-Name-".rand(0000,9999).".".$ext;

                        // B. Upload Image
                        // Get The Source Path and Destination Path

                        // Source Path  is Current Location of Image
                        $src = $_FILES['image']['tmp_name'];

                        // Destination Path For The Image To Upload
                        $dst = "../images/food/".$image_name;

                        // Upload Image 
                        $upload = move_uploaded_file($src , $dst);

                        // Check Image Uploaded
                        if($upload == FALSE)
                        {
                            // Failed To Upload
                            $_SESSION['upload'] = "<div class='error'>Failed To Upload Image</div>";
                            // Redirect to Food Menu
                            header('location:'.SITEURL.'admin/add-food.php');
                            die();
                        }
                    }
                }

                else
                {
                    $image_name = "";   // Blank Image
                }

                // 3. Insert Into Database

                $sql2 = "INSERT INTO tbl_food SET
                         title = '$title',
                         description = '$description',
                         price = $price,
                         image_name = '$image_name',
                         category_id = $category,
                         featured = '$featured',
                         active = '$active'
                ";

                // Execute

                $res2 = mysqli_query($conn , $sql2);

                // 4. Redirect
                // Check whether data inserted or not

                if($res2 == TRUE)
                {
                    $_SESSION['add'] = "<div class = 'success'>Food Added Successfully</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }

                else
                {
                    $_SESSION['add'] = "<div class = 'error'>Food Added Failed</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }

               
            }

        ?>

    </div>
</div>



<?php include('partials/footer.php'); ?>