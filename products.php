<?php
session_start();
$con=mysqli_connect("localhost","root","1234","projectdb");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id']) && isset($_SESSION['UserL'])) {
        $product_id = $_POST['product_id'];
        $maxquanity=$_POST['product_qu'];
        $user_id = $_SESSION['UserL']['id'];
        
        
        $query = "SELECT * FROM temp_orders WHERE user_id = '$user_id' AND item_id = '$product_id'";
        $result = mysqli_query($con, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $current_quantity = $row['quanity'];
            if($maxquanity - $current_quantity > 0){
                $sql = "UPDATE temp_orders SET quanity = quanity + 1 WHERE user_id = '$user_id' AND item_id = '$product_id'";
                if ($con->query($sql) === TRUE) {
                    echo "Record updated successfully";
                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit;
                } else {
                    echo "Error updating record: " . $con->error;
                }
            } else {
                echo "<script>alert('No More Items')</script>";
            }
        } else {
            $sql = "INSERT INTO temp_orders (user_id, item_id, quanity) VALUES ('$user_id', '$product_id', 1)";
            if ($con->query($sql) === TRUE) {
                echo "Record inserted successfully";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit;
            } else {
                echo "Error inserting record: " . $con->error;
            }
        }       
    } else if(!isset($_SESSION['UserL'])){
        echo "<script>alert('Please Login First to access cart')</script>";
    }
}



$result = mysqli_query($con,"SELECT * FROM menu_items");
$array = array();
$i=0;
while ($row = mysqli_fetch_array($result)) {
    if($row['6']>0){
        $array[$i]["item_id"]=$row['0'];
        $array[$i]["resturant_id"]=$row['1'];
        $array[$i]["name"]=$row['2'];
        $array[$i]["description"]=$row['3'];
        $array[$i]["price"]=$row['4'];
        $array[$i]["img"]=$row['5'];
        $array[$i]["quantity"]=$row['6'];
        $i++;
    }
}
$products=$array;

usort($products, function($a, $b) {
    if ($a['price'] == $b['price']) {
        return 0;
    } elseif ($a['price'] < $b['price']) {
        return 1;
    } else {
        return -1;
    }
});
mysqli_close($con);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <link rel="stylesheet" href="css/products.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h1>Our Menu</h1>
    <?php
    if (isset($_POST['most_expensive'])) {
        $maxprice = $products[0]['price']; 
        $mostExpensiveProduct = $products[0];
    
        foreach ($products as $product) {
            if ($product['price'] > $maxprice) {
                $maxprice = $product['price'];
                $mostExpensiveProduct = $product;
            }
        }
        echo "<p id='removethis'>The most expensive product is: " . $mostExpensiveProduct['name'] . " and the price is $" . $mostExpensiveProduct['price'] . "</p>";
    }
    ?>
    <div class="form-container">
    <form method="post">
        <button type="submit" name="most_expensive">Most Expensive Product</button>
    </form>
    <button id="change-text-color">Change Text Color</button>
    <button id="reset">Remove text under the First button</button>
    </div>
   
    <div class="product-container">
        <?php
        foreach ($products as $product) {
            echo '<div class="product">';
            echo '<img src="' . $product['img'] . '" alt="' . $product['name'] . '">';
            echo '<div class="product-details">';
            echo "<h2 class='product-name'> " . $product['name'] . "</h2>";
            echo "<p>price: $" . $product['price'] . "</p>";
            echo '<form class="add-to-cart-form" method="post" action="' . $_SERVER['PHP_SELF'] . '">';
            echo '<input type="hidden" name="product_id" value="' . $product['item_id'] . '">';
            echo '<input type="hidden" name="product_qu" value="' . $product['quantity'] . '">';
            echo '<p>' . $product['name'] . '</p>';
            echo '<button type="submit" class="add-to-cart-btn">Add to Cart</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
    <script src="js/products.js"></script>
    <script>

    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const form = button.closest('.add-to-cart-form');
            form.submit();
        });
    });
    </script> 
</body>
</html>
