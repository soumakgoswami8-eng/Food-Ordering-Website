<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Change Password</h1>
        <br> <br>

        <?php
            if(isset($_GET['id']))
            {
                $id = $_GET['id'];
            }
        ?>

        <form action="" method="POST">

            <table class="tbl-30">
                <tr>
                    <td>Current Password:</td>
                    <td>
                        <input type="password" name="current_password" placeholder="Current Password">
                    </td>
                </tr>

                <tr>
                    <td>New Password:</td>
                    <td>
                        <input type="password" name="new_password" placeholder="New Password">
                    </td>
                </tr>

                <tr>
                    <td>Confirm Password:</td>
                    <td>
                        <input type="password" name="confirm_password" placeholder="Confirm Password">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Change Password" class="btn-secondary">
                    </td>
                </tr>

            </table>


        </form>

    </div>
</div>

<?php 
    // Check Whether Button Clicked Or Not
    if(isset($_POST['submit']))
    {
        //echo "CLicked";

        // 1. Get The Data From Form
        $id = $_POST['id'];
        $current_password = md5($_POST['current_password']);
        $new_password = md5($_POST['new_password']);
        $confirm_password = md5($_POST['confirm_password']);

        // 2. Check Whether the User with Current Id And Password Exists Or Not
        $sql = "SELECT * FROM tbl_admin WHERE id = $id AND password = '$current_password'";

        // Execute
        $res = mysqli_query($conn , $sql);

        if($res == TRUE)
        {
            // Check Data Available
            $count = mysqli_num_rows($res);

            if($count==1)
            {
                // User Exists and Password Can Be Changed
                //echo "User Found";

                if($new_password == $confirm_password)
                {
                    // Update The Password
                    $sql2 = "UPDATE tbl_admin SET
                            password = '$new_password'
                            WHERE id = $id
                    ";

                    // Execute The Query
                    $res2 = mysqli_query($conn , $sql2);

                    // Check Whether Query Executed Or Not
                    if($res2 == TRUE)
                    {
                        // Display Message
                        $_SESSION['change-pwd'] = "<div class=success>Password Changed Successfully</div>";
                        //Redirect
                        header('location:'.SITEURL.'admin/manage-admin.php');
                    }

                    else
                    {
                        // Display Error Message
                        $_SESSION['change-pwd'] = "<div class=error>Failed To Change Password</div>";
                        //Redirect
                        header('location:'.SITEURL.'admin/manage-admin.php');
                    }
                }

                else
                {
                    // Redirect To Manage Admin Page with Error Message
                    $_SESSION['pwd-not-match'] = "<div class=error>Password Did Not Match</div>";
                    //Redirect
                    header('location:'.SITEURL.'admin/manage-admin.php');
                }
            }

            else
            {
                $_SESSION['user-not-found'] = "<div class=error>User Not Found.</div>";
                //Redirect
                header('location:'.SITEURL.'admin/manage-admin.php');
            }
        }
        // 3. Check Whether The New Password and Confirm Password Match or Not

        // 4. Change Password if all above is true
    }

?>



<?php include('partials/footer.php'); ?>