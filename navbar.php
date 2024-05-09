<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="topnav">
    <a href="homepage.php">Home</a>
    <a href="products.php">Products</a>
    <a href="contact.php">Contact Us</a>
    <?php
        if(isset($_SESSION['UserL'])) {
            echo '<div class="right-links">';
            if(isset($_SESSION['admin'])) 
                echo '<a href="admin/index.php">admin</a>';
            echo '<a href="cart.php"><i class="fas fa-shopping-cart"></i>cart</a>';
            echo '<a href="profile.php"><i class="fas fa-user"></i> Profile</a>';
            echo '<a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>';
            echo '</div>';
        } else {
            echo '<div class="right-links">';
            echo '<a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>';
            echo '<a href="register.php"><i class="fas fa-user-plus"></i> Register</a>';
            echo '</div>';
        }
    ?>
</div>
</body>
</html>
