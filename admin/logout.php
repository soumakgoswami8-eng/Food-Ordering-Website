<?php 

    // include constants.php for url
    include('../config/constants.php');
    // 1. Destroy The Session 
    session_destroy();  // Unsets $_SESSION['user];

    // 2. Redirect To LogIn Page
    header('location:'.SITEURL.'admin/login.php')

?>