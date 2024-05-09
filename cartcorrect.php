<?php
session_start();
    $con=mysqli_connect("localhost","root","1234","projectdb");
    if (mysqli_connect_errno())
    {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    if (!isset($_SESSION['UserL'])) {
        header("Location: login.php");
        exit();
    }
    $user_id = $_SESSION['UserL']['id'];
    if(isset($_SESSION['dontb'])){
        header("Location: homepage.php");
        exit;
    }
    $query = "SELECT * FROM temp_orders WHERE user_id = '$user_id'";
    $result = mysqli_query($con, $query);
    $data = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    } else {
        echo "Error: " . mysqli_error($con);
    }
    
    $sql = "SELECT * FROM orders";
    $result = $con->query($sql);
        $orders = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
    }  
    $result = mysqli_query($con,"SELECT * FROM menu_items");
    $array = array();
    $i=0;
    while ($row = mysqli_fetch_array($result)) {
        $array[$i]["item_id"]=$row['0'];
        $array[$i]["resturant_id"]=$row['1'];
        $array[$i]["name"]=$row['2'];
        $array[$i]["description"]=$row['3'];
        $array[$i]["price"]=$row['4'];
        $array[$i]["img"]=$row['5'];
        $array[$i]["quantity"]=$row['6'];
        $i++;
    }
    $products=$array;
    $orderedQuantityarr=array();
    foreach($products as $product){
        $query = "SELECT * FROM order_items WHERE item_id = '".$product['item_id']."'";
        $result = mysqli_query($con, $query);
        if ($result) 
            while ($row = mysqli_fetch_assoc($result)) 
                if(!isset($orderedQuantityarr[$product['item_id']]))
                    $orderedQuantityarr[$product['item_id']]=$row['quanity'];
                else 
                    $orderedQuantityarr[$product['item_id']]+=$row['quanity'];
    }
    foreach($products as $product){
        if(isset($orderedQuantityarr[$product['item_id']])){
            $currentQuantity = $product['quantity'];
            $newQuantity = $currentQuantity - $orderedQuantityarr[$product['item_id']];
            $item_id = $product['item_id'];
            if($newQuantity <= 0) $newQuantity=0;
            $query_update_quantity = "UPDATE menu_items SET quantity = '$newQuantity' WHERE item_id = '".$item_id."'";
            mysqli_query($con, $query_update_quantity);
        }
    }
    mysqli_close($con);
    if(!isset($_SESSION['dontb'])){
        $_SESSION['dontb']=1;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Order</title>
    
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="container">
        <h1>Confirm Order</h1>
        <div class="order-details">
            <p>Your order has been confirmed. Thank you for shopping with us!</p>
            <?php
                foreach ($orders as $order) {
                    echo "Order ID: " . $order["order_id"] . " - Date: " . $order["OrderDate"] . "<br>";
                }
            ?>
            <a href="homepage.php">Click Here To Go Back</a>
        </div>
    </div>
</body>
</html>
