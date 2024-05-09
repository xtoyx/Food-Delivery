<?php    
    session_start();
    if (!isset($_SESSION['UserL'])) {
        header("Location: login.php");
        exit();
    }

    $con = mysqli_connect("localhost", "root", "1234", "projectdb");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }

    $user_id = $_SESSION['UserL']['id'];
    $query = "SELECT * FROM users WHERE user_id = '$user_id'";
    $result = mysqli_query($con, $query);
    $user = mysqli_fetch_assoc($result);

    $query_orders = "SELECT * FROM orders WHERE user_id = '$user_id'";
    $result_orders = mysqli_query($con, $query_orders);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="css/profile.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1>Welcome to Your Profile, <?php echo $user['Username']; ?>!</h1>

        <div class="user-info">
            <img src="<?php echo $user['img']; ?>" alt="Profile Image">
            <h2><?php echo $user['Username']; ?></h2>
            <p>Email: <?php echo $user['e-mail']; ?></p>
        </div>

        <div class="orders-list">
            <h2>Your Orders</h2>
                <?php
                while ($order = mysqli_fetch_assoc($result_orders)) {
                    echo "<div class='order'>";
                    echo "<p>Order ID: " . $order['order_id'] . " - Total Price: $" . $order['totalPrice'] . " - Date: ".$order['OrderDate']."</p>";
                    $order_id = $order['order_id'];
                    $query_order_items = "SELECT * FROM order_items WHERE order_id = '$order_id'";
                    $result_order_items = mysqli_query($con, $query_order_items);
                    $item_ids=array();
                    $i=0;
                    while ($order_item = mysqli_fetch_assoc($result_order_items)) {
                        $item_ids[$i]=$order_item['item_id'];   
                        $i++;
                    }
                    echo "<form action='order_detail.php' method='post'>";
                    echo "<input type='hidden' name='item_ids' value='" . implode(',',$item_ids) . "'>";
                    echo "<button type='submit'>View Details</button>";
                    echo "</form>";
                    echo "</div>";
                }
                ?>                
            
        </div>
    </div>
</body>
</html>

