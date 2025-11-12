    <?php include('partials-front/menu.php'); ?>

        <?php

            // Check id set?
            if(isset($_GET['food_id']))
            {
                // Details Of Food
                $food_id = $_GET['food_id'];

                // Selected Food Details
                $sql = "SELECT * FROM tbl_food WHERE id = $food_id";
                // Execute
                $res = mysqli_query($conn , $sql);
                // Count Rows
                $count = mysqli_num_rows($res);
                // Check Whether Data is Available?
                if($count == 1)
                {
                    // We Have Data
                    // Get Data From Database
                    $row = mysqli_fetch_assoc($res);
                    $title = $row['title'];
                    $price = $row['price'];
                    $image_name = $row['image_name'];
                }

                else
                {
                    // No Data
                    // Redirect
                    header('location:'.SITEURL);
                }
            }

            else
            {
                // Redirect to HomePage
                header('location:'.SITEURL);
            }

        ?>


    <!-- fOOD sEARCH Section Starts Here -->
    <section class="food-search">
        <div class="container">
            
            <h2 class="text-center text-white">Fill this form to confirm your order.</h2>

            <form action="" method="POST" class="order">
                <fieldset>
                    <legend>Selected Food</legend>

                    <div class="food-menu-img">
                        <?php 
                        
                            // Check Image Availble?
                            if($image_name == "")
                            {
                                // Not Available
                                echo "<div class='error'>Image Not Available.</div>";
                            }

                            else
                            {
                                ?>

                                <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" class="img-responsive img-curve">

                                <?php
                            }
                        ?>                
                    </div>
    
                    <div class="food-menu-desc">
                        <h3><?php echo $title; ?></h3>
                        <input type="hidden" name="food" value="<?php echo $title; ?>">
                        <p class="food-price">$<?php echo $price; ?></p>
                        <input type="hidden" name="price" value="<?php echo $price; ?>">
                        <div class="order-label">Quantity</div>
                        <input type="number" name="qty" class="input-responsive" value="1" required>
                        
                    </div>

                </fieldset>
                
                <fieldset>
                    <legend>Delivery Details</legend>
                    <div class="order-label">Full Name</div>
                    <input type="text" name="full-name" placeholder="E.g. Soumak Goswami" class="input-responsive" required>

                    <div class="order-label">Phone Number</div>
                    <input type="tel" name="contact" placeholder="E.g. 9038xxxxxx" class="input-responsive" required>

                    <div class="order-label">Email</div>
                    <input type="email" name="email" placeholder="E.g. hi@soumakgoswami.com" class="input-responsive" required>

                    <div class="order-label">Address</div>
                    <textarea name="address" rows="10" placeholder="E.g. Street, City, Country" class="input-responsive" required></textarea>

                    <input type="submit" name="submit" value="Confirm Order" class="btn btn-primary">
                </fieldset>

            </form>

            <?php 
            
                // Check Button Clicked?
                if(isset($_POST['submit']))
                {
                    // CLicked and Get Details Into Database
                    $food = $_POST['food'];
                    $price = $_POST['price'];
                    $qty = $_POST['qty'];
                    $total = $price * $qty; // Total Price

                    $order_date = date("Y-m-d h:i:sa"); // Order Date and Time
                    $status = "Ordered";    // Ordered , On-Delivery , Delivered , Cancelled
                    $customer_name = $_POST['full-name'];
                    $customer_contact = $_POST['contact'];
                    $customer_email = $_POST['email'];
                    $customer_address = $_POST['address'];

                    // Save Order In DataBase
                    // Create SQL
                    $sql2 = "INSERT INTO tbl_order SET
                    
                            food = '$food',
                            price = $price,
                            qty = $qty,
                            total = $total,
                            order_date = '$order_date',
                            status = '$status',
                            customer_name = '$customer_name',
                            customer_contact = '$customer_contact',
                            customer_email = '$customer_email',
                            customer_address = '$customer_address'                    
                    ";

                    // Execute
                    $res2 = mysqli_query($conn , $sql2);

                    // Check Executed

                    if($res2 == TRUE)
                    {
                        // Query Executed and Order Saved
                        $_SESSION['order'] = "<div class='success text-center'>Order Placed Successfully</div>";
                        // Redirect
                        header('location:'.SITEURL);
                    }

                    else
                    {
                        // Failed To Save Order
                        $_SESSION['order'] = "<div class='error tex-center'>Order Failure</div>";
                        // Redirect
                        header('location:'.SITEURL);
                    }

                }
            
            ?>

        </div>
    </section>
    <!-- fOOD sEARCH Section Ends Here -->

    <?php include('partials-front/footer.php'); ?>