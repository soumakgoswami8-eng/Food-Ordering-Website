<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Admin</h1>
        <br> <br>

        <?php 
            if(isset($_SESSION['add'])) // Checking Whether the Session is Set Or Not
            {
                echo $_SESSION['add'];  // Display the Session Message If Set
                unset($_SESSION['add']);    // Remove Session Message
            }
        ?>

        <form action="" method="POST">

            <table class="tbl-30">
                <tr>
                    <td>Full Name: </td>
                    <td>
                        <input type="text" name="full_name" placeholder="Enter Your Name">
                    </td>
                </tr>
                <tr>
                    <td>Username: </td>
                    <td>
                        <input type="text" name="username" placeholder="Your Username">
                    </td>
                </tr>

                <tr>
                    <td>Password: </td>
                    <td>
                        <input type="password" name="password" placeholder="Your Password">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Admin" class="btn-secondary">
                    </td>
                </tr>
            </table>

        </form>


    </div>
</div>

<?php include('partials/footer.php'); ?>

<?php 
    // Process The Value from Form and Save it in database
    // Check where the submit button is clicked or not

    if(isset($_POST['submit']))
    {
        //Button Clicked
        //echo "Button Clicked.";

        // 1. Get The Data from form
        $full_name = $_POST['full_name'];
        $username = $_POST['username'];
        $password = md5($_POST['password']);    // Password Encryption with md5

        // 2. SQL Query to save data into database
        $sql = "INSERT INTO tbl_admin SET 
                full_name = '$full_name',
                username = '$username',
                password = '$password'
        ";

        // 3.Execute Query and Save Data into Database
        
        $res = mysqli_query($conn , $sql) or die(mysqli_error());

        // 4. Check whether the data is inserted or not and display appropriate message

        if($res == TRUE)
        {
            // Data Inserted
            //echo "Data Inserted";
            //Create a session variable to display message
            $_SESSION['add'] = "<div class='success'>Admin Added Successfully</div>";
            //Redirect Page TO MANAGE ADMIN
            header("location:".SITEURL.'admin/manage-admin.php');
        }

        else
        {
            // Data Not Inserted
            //echo "Failed To Insert Data";
            $_SESSION['add'] = "<div class='error'>Failed To Add Admin</div>";
            //Redirect Page TO ADD ADMIN
            header("location:".SITEURL.'admin/manage-admin.php');
        }

    }

   
?>