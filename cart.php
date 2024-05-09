<?php
session_start();

    $con=mysqli_connect("localhost","root","1234","projectdb");
    if (mysqli_connect_errno())
    {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    $result = mysqli_query($con,"SELECT * FROM menu_items");
    $isthereany=true;
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
    $productsfromdatabase=$array;
    $products=array();
    $user_id="";
    if(!isset($_SESSION['UserL'])){
       $isthereany=false;
    }
    else {
        $user_id = $_SESSION['UserL']['id'];
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
        $productids=$data;
        $i=0;
        for($x=0;$x<sizeof($productids);$x++){
            $product=$productsfromdatabase[$productids[$x]['item_id']-1];
            $products[$i]["item_id"]=$product["item_id"];
            $products[$i]["resturant_id"]=$product["resturant_id"];
            $products[$i]["name"]=$product["name"];
            $products[$i]["description"]=$product["description"];
            $products[$i]["price"]=$product["price"];
            $products[$i]["img"]=$product["img"];
            $products[$i]["maxqu"]=$product["quantity"];
            $products[$i]["counter"]=$productids[$x]['quanity'];
            $i++;   
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['action']) && $_POST['action'] == 'plus') {
            $item_id = $_POST['item_id'];
            foreach($products as $product)
                if($product['item_id']==$item_id){
                    $maxquanity=$product['maxqu'];
                    $sql = "SELECT quanity FROM temp_orders WHERE user_id = '$user_id' AND item_id = '$item_id'";
                    $result = mysqli_query($con, $sql);
                    if ($result) {
                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $current_quantity = $row['quanity'];
                            if($maxquanity - $current_quantity > 0){
                                $sql = "UPDATE temp_orders SET quanity = quanity + 1 WHERE user_id = '$user_id' AND item_id = '$item_id'";
                                if ($con->query($sql) === TRUE) {
                                    echo "Record updated successfully";
                                    header("Location: ".$_SERVER['PHP_SELF']);
                                    exit;
                                } else {
                                    echo "Error updating record: " . $con->error;
                                }
                            } 
                        } 
                    }
                   
                }
                    
        } elseif(isset($_POST['action']) && $_POST['action'] == 'minus') {
            $item_id = $_POST['item_id'];  
            foreach($products as $product)
                if($product['item_id']==$item_id){
                    print_r($product['counter']);
                    $sql="";
                    if($product['counter'] > 1)
                        $sql = "UPDATE temp_orders SET quanity = quanity - 1 WHERE user_id = '$user_id' AND item_id = '$item_id'";
                    else 
                        $sql = "DELETE FROM temp_orders WHERE user_id = '$user_id' AND item_id = '$item_id'";
                    if ($con->query($sql) === TRUE) {
                        echo "Record updated successfully";
                        header("Location: ".$_SERVER['PHP_SELF']);
                        exit;
                    } else {
                        echo "Error updating record: " . $con->error;
                    }
            }
        } elseif(isset($_POST['action']) && $_POST['action'] == 'remove') {
            $item_id = $_POST['item_id'];  
            foreach($products as $product)
                if($product['item_id']==$item_id){
                    $sql = "DELETE FROM temp_orders WHERE user_id = '$user_id' AND item_id = '$item_id'";
                    if ($con->query($sql) === TRUE) {
                        echo "Record removed successfully";
                        header("Location: ".$_SERVER['PHP_SELF']);
                        exit;
                    } else {
                        echo "Error removing record: " . $con->error;
                    }
                }
        } else {
            echo "Accept";
            $total_price=0;
            foreach($products as $product){
                $total_price += $product['price']*$product['counter'];
            }
            $sql = "INSERT INTO `orders` (`user_id`, `OrderDate`, `totalPrice`) VALUES ('".$user_id."', CURDATE(), '".$total_price."')";
            if ($con->query($sql) === TRUE) {
                echo "Record updated successfully";
                $order_id = $con->insert_id;
                echo $order_id;
                foreach($products as $product){
                    $sql2="INSERT INTO `order_items` (`order_id`, `item_id`, `quanity`, `resturant_id`) VALUES ('".$order_id."', '".$product['item_id']."', '".$product['counter']."', '".$product['resturant_id']."');";
                    if ($con->query($sql2) === TRUE) {
                        echo "Record updated successfully";
                    }else {
                        echo "Error updating record: " . $con->error;
                    }
                    if(isset($_SESSION['dontb'])){
                        unset($_SESSION['dontb']);
                    }
                    $sql = "DELETE FROM temp_orders WHERE user_id = '$user_id' AND item_id = '" . $product['item_id'] . "'";
                    if ($con->query($sql) === TRUE) {
                        echo "Record updated successfully";
                        header("Location: cartcorrect.php");
                    }else {
                        echo "Error updating record: " . $con->error;
                    }
                }
            } else {
                echo "Error updating record: " . $con->error;
            }
        }
    }
    mysqli_close($con);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/cart.css?v=<?php echo time(); ?>">
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="container">
        <h1>Shopping Cart</h1>
        <div class="cart-items">
            <?php
            if($isthereany){
                foreach($products as $product){
                    echo "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
                    echo "<div class='cart-item'>";
                    echo "<input type='hidden' name='item_id' value='" . $product['item_id'] . "'>";
                    echo "<img src='" . $product['img'] . "' alt='" . $product['name'] . "'>";
                    echo "<div class='item-details'>";
                    echo "<h3>" . $product['name'] . "</h3>";
                    echo "<p>" . $product['description'] . "</p>";
                    echo "</div>";
                    echo "<span style='margin-right:3%'>" . $product['counter'] . "</span>";
                    echo "<div class='quantity'>";
                    echo "<button class='quantity-btn' type='submit' name='action' value='plus'><i class='fas fa-plus'></i></button>";
                    echo "<button class='quantity-btn' type='submit' name='action' value='minus'><i class='fas fa-minus'></i></button>";
                    echo "<button class='remove-btn' type='submit' name='action' value='remove'><i class='fas fa-trash-alt'></i></button>";
                    echo "</div>";
                    echo "</div>";
                    echo "</form>";
                }
                if(sizeof($products)==0){
                    echo "<p style='text-align:center'>Please go select some items</p>";
                }
            }
            else {
                echo "<p>Please Login</p>";
            }
        ?>
        </div>
        <form id="acceptForm" method="POST">
            <button class="checkout-btn" type="submit">Accept</button>
        </form>
    </div>
</body>
</html>
