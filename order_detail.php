<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="css/profile.css?v=<?php echo time(); ?>">
</head>
<body>

<?php
session_start();
if (!isset($_POST['item_ids'])) {
    header("Location: profile.php");
    exit();
}

$item_ids = $_POST['item_ids'];

if (!empty($item_ids) && is_string($item_ids)) {
    $item_ids_array = explode(',', $item_ids);

    $con = mysqli_connect("localhost", "root", "1234", "projectdb");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }

    include 'navbar.php';

    echo "<div class='container'>";
    echo "<h1>Order Details</h1>";
    echo "<div class='orders-list'>";
    foreach ($item_ids_array as $item_id) {
        // Fetch the details of the selected order item from the database
        $query = "SELECT * FROM menu_items WHERE item_id = '$item_id'";
        $result = mysqli_query($con, $query);
        if (!$result) {
            echo "Error fetching order item details: " . mysqli_error($con);
            exit();
        }

        if (mysqli_num_rows($result) == 0) {
            echo "Order item not found.";
            continue; 
        }

        $order_item = mysqli_fetch_assoc($result);
        echo "<div class='order'>";
        echo "<div class='order-item'>";
        echo "<img src='" . $order_item['img'] . "' alt='" . $order_item['name'] . "'>";
        echo "<div>";
        echo "<p>Name: " . $order_item['name'] . "</p>";
        echo "<p>Description: " . $order_item['description'] . "</p>";
        echo "<p>Price: $" . $order_item['price'] . "</p>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";
    echo "</div>";

    mysqli_close($con);
} else {
    echo "No item IDs found or invalid data.";
}
?>
</body>
</html>