<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Admin</h1>
        <br><br>

        <?php 
            // 1. Get the id of Selected Admin
            $id = $_GET['id'];

            // 2. Create SQL Query To Get The Details
            $sql = "SELECT * FROM tbl_admin WHERE id = $id";

            // Execute The Query
            $res = mysqli_query($conn , $sql);

            // Check Whether Query is Executed or Not
            if($res == TRUE)
            {
                // Check Whether The Data Available or Not
                $count = mysqli_num_rows($res);
                // Check Whether we have admin data or not
                if($count == 1)
                {
                    // Get The Details
                    //echo "Admin Available";
                    $row = mysqli_fetch_assoc($res);

                    $full_name = $row['full_name'];
                    $username = $row['username'];
                } 

                else
                {
                    // Redirect to Manage Admin Page
                    header('location:'.SITEURL.'admin/manage-admin.php');
                }
            }
        ?>


        <form action="" method="POST">

            <table class="tbl-30">
                <tr>
                    <td>Full Name: </td>
                    <td>
                        <input type="text" name="full_name" value="<?php echo $full_name; ?>">
                    </td>
                </tr>

                <tr>
                    <td>Username: </td>
                    <td>
                        <input type="text" name="username" value="<?php echo $username; ?>">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Update Admin" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>
    </div>
</div>

<?php 

    // Check Whether Submit Button Clicked Or Not
    if(isset($_POST['submit']))
    {
        //echo "Button Clicked";
        // Get All the values from form to update
        $id = $_POST['id'];
        $full_name = $_POST['full_name'];
        $username = $_POST['username'];

        // Create a SQL query to update
        $sql = "UPDATE tbl_admin SET
        full_name = '$full_name',
        username = '$username' 
        WHERE id = '$id'
        ";

        // Execute The Query
        $res = mysqli_query($conn , $sql);

        // Check whether query executed or not
        if($res == TRUE)
        {
            // Query Executed
            $_SESSION['update'] = "<div class='success'>Admin Updated Succesfully</div>";

            // Redirect
            header('location:'.SITEURL.'admin/manage-admin.php');
        }

        else
        {
            // Failed to Update
            $_SESSION['update'] = "<div class='erroe'>Failed To Update</div>";

            // Redirect
            header('location:'.SITEURL.'admin/manage-admin.php');
        }
    }

?>


<?php include('partials/footer.php'); ?>