<?php include('partials/menu.php'); 

        ob_start();
?>

<?php 

    // Check ID is Set Or Not
    if(isset($_GET['id']))
    {
        $id = $_GET['id'];

        // SQL Query
        $sql2 = "SELECT * FROM tbl_food WHERE id = $id";
        // Execute
        $res2 = mysqli_query($conn , $sql2);
        
        // Get value based on query
        $row2 = mysqli_fetch_assoc($res2);

        // Get The Individual Values
        $title = $row2['title'];
        $description = $row2['description'];
        $price = $row2['price'];
        $current_image = $row2['image_name'];
        $current_category = $row2['category_id'];
        $featured = $row2['featured'];
        $active = $row2['active'];
    }

    else
    {
        // Redirect
        header('location:'.SITEURL.'admin/manage-food.php');
    }

?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Food</h1>
        <br><br>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                
                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="title" value="<?php echo $title;?>">
                    </td>
                </tr>

                <tr>
                    <td>Description:</td>
                    <td>
                        <textarea name="description" cols="30" rows="5"><?php echo $description; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Price:</td>
                    <td>
                        <input type="number" step="0.01" name="price" value="<?php echo $price;?>">
                    </td>
                </tr>

                <tr>
                    <td>Current Image:</td>
                    <td>
                        <?php 
                        
                            if($current_image == "")
                            {
                                echo "<div class='error'>Image Not Available</div>";
                            }

                            else
                            {
                                ?>
                                <img src="<?php echo SITEURL;?>images/food/<?php echo $current_image;?>" width="150px">
                                <?php
                            }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>Select New Image:</td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Category</td>
                    <td>
                        <select name="category">
                            <?php 
                                // Query To Get Active Categories
                                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                // Execute
                                $res = mysqli_query($conn , $sql);

                                // Count Rows
                                $count = mysqli_num_rows($res);

                                // Check whether category available
                                if($count>0)
                                {
                                    while($row=mysqli_fetch_assoc($res))
                                    {
                                        $category_title = $row['title'];
                                        $category_id = $row['id'];
                                        
                                        //echo "<option value='$category_id'>$category_title</option>";
                                        ?>

                                        <option <?php if($current_category == $category_id){echo "selected";} ?> value="<?php echo $category_id; ?>"><?php echo $category_title; ?></option>

                                        <?php
                                    }
                                }

                                else
                                {
                                    echo "<option value='0'>Category Not Available.</option>";
                                }
                            
                            ?>
                            
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Featured</td>
                    <td>
                        <input <?php if($featured == "Yes"){echo "checked";} ?> type="radio" name="featured" value="Yes">Yes
                        <input <?php if($featured == "No"){echo "checked";} ?> type="radio" name="featured" value="No">No
                    </td>
                </tr>

                <tr>
                    <td>Active</td>
                    <td>
                        <input <?php if($active == "Yes"){echo "checked";} ?> type="radio" name="active" value="Yes">Yes
                        <input <?php if($active == "No"){echo "checked";} ?> type="radio" name="active" value="No">No
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="submit" name="submit" value="Update Food" class="btn-secondary">
                    </td>
                </tr>

            </table>
        </form>

        <?php

            if(isset($_POST['submit']))
            {
                //echo "Button Clicked";

                // Get All Details
                $id = $_POST['id'];
                $title = $_POST['title'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $current_image = $_POST['current_image'];
                $category = $_POST['category'];
                
                $featured = $_POST['featured'];
                $active = $_POST['active'];

                // Upload image if selected
                // Remove the image if new image uploaded and current image is present
                if(isset($_FILES['image']['name']))
                {
                    $image_name = $_FILES['image']['name'];

                    if($image_name!="")
                    {
                        // Image Available 
                        // Rename
                        $temp = explode('.',$image_name);
                        $ext = end($temp);

                        $image_name = "Food-Name-".rand(0000,9999).'.'.$ext;

                        // Get The Source and Destination Path
                        $src_path = $_FILES['image']['tmp_name'];
                        $dest_path = "../images/food/".$image_name;

                        // Upload
                        $upload = move_uploaded_file($src_path , $dest_path);
                        // Image is Uploaded or Not
                        if($upload == FALSE)
                        {
                            $_SESSION['upload'] = "<div class='error'>Failed To Upload Image</div>";
                            header('location:'.SITEURL.'admin/manage-food.php');
                            die();  // Stop The Process
                        }

                        // B. Remove Current Image
                        if($current_image != "")
                        {
                            $remove_path = "../images/food/".$current_image;

                            $remove = unlink($remove_path);

                            // Check Image Is Removed Or Not
                            if($remove==FALSE)
                            {
                                $_SESSION['remove-failed'] = "<div class='error'>Failed To Remove Current Image</div>";
                                header('location:'.SITEURL.'admin/manage-food.php');
                                die();
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

                // Update Food In Database
                $sql3 = "UPDATE tbl_food SET
                        title = '$title',
                        description = '$description',
                        price = $price,
                        image_name = '$image_name',
                        category_id = '$category',
                        featured = '$featured',
                        active = '$active'
                        WHERE id = $id
                ";

                // Execute
                $res3 = mysqli_query($conn , $sql3);

                // Check Whether Query Executed
                // Redirect
                if($res3 == TRUE)
                {
                    $_SESSION['update'] = "<div class='success'>Food Updated Successfully</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }

                else
                {
                    $_SESSION['update'] = "<div class='error'>Food Updation Failed</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }
            }

        ?>

    </div>
</div>


<?php include('partials/footer.php'); 
      ob_end_flush();
?>