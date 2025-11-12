<?php  
    // Authorisation - Access Control
    // Check Whether Logged In Or Not
    if(!isset($_SESSION['user']))       // If User Session Is Not Set
    {
        // User Not Logged In
        // Redirect To Login Page
        $_SESSION['no-login-message'] = "<div class='error text-center'>Please Login To Access Admin Panel</div>";
        // Redirect
        header('location:'.SITEURL.'admin/login.php');
    }
?>