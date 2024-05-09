<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../Login.php");
    exit;
}

$con = mysqli_connect("localhost", "root", "1234", "projectdb");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$users_sql = "SELECT * FROM users";
$users_result = $con->query($users_sql);

$products_sql = "SELECT * FROM menu_items";
$products_result = $con->query($products_sql);

$i = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_products'])) {
    if (isset($_POST['selected-products'])) {
        $selectedProducts = json_decode($_POST['selected-products'], true);
        
        if(isset($_SESSION['selected_products']) || !isset($_SESSION['selected_products'])){
            $_SESSION['selected_products']=$selectedProducts;
        }
        
        header("Location: edit.php");
        exit;

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="../css/admin.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link rel="stylesheet" href="../css/navbar.css?v=<?php echo time(); ?>">

</head>
<body>
    <?php include"../navbar_admin.php"; ?>
    <div class="container">
        <h1 class="mt-4">Admin Dashboard</h1>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">Users</h5>
            </div>
            <div class="card-body">
                <div class="user-list">
                    <?php
                    while($row = $users_result->fetch_assoc()) {
                        echo "<div class='user'>";
                        echo "<a href='user_details.php?user_id=" . $row["user_id"] . "'>"; // Link to user details page
                        echo "<img src='../" . $row["img"] . "' alt='User Image' class='user-img'>";
                        echo "<div class='user-details'>";
                        echo "<h6>" . $row["Username"] . "</h6>";
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
            <div class="card-footer">
                <a  class="btn btn-primary view-users-button">View Users</a> 
                <button class="btn btn-success add-product-button">Add User</button>
                <button class="btn btn-danger remove-product-button"style="display:none">Remove User</button>
            </div>
        </div>

        <form id="selected-products-form" method="post" action="">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Products</h5>
                </div>
                <div class="card-body">
                    <div class="product-list-container">
                        <?php
                        if ($products_result->num_rows > 0) {
                            while($row = $products_result->fetch_assoc()) {
                                $i++;
                                echo "<div class='product'>"; 
                                echo "<input type='checkbox' class='product-checkbox'>";
                                echo "<img src='../" . $row["img"] . "' alt='" . $row["name"] . "' class='product-img'>";
                                echo "<div class='product-details'>";
                                echo "<h6>" . $row["name"] . "</h6>";
                                echo "<p>Description: " . $row["description"] . "</p>";
                                echo "<p>Price: $" . $row["price"] . "</p>";
                                echo "<p>Quantity: " . $row["quantity"] . "</p>";
                                echo "<h7 style='display:none'>" . $row["item_id"] . "</h7>";
                                echo "</div>";
                                echo "</div>";
                            }
                        } 
                        ?>
                    </div>
                </div>
                <div class="card-footer">
                    <a class="btn btn-primary view-products-button">View Products</a>
                    <button class="btn btn-success add-product-button">Add Product</button>
                    <button class="btn btn-danger delete-btn" style="display: none;">Delete</button>
                    <button type="submit" name="edit_products" class="btn btn-primary edit-btn" style="display: none;">Edit</button>
                </div>
            </div>
            <input type="hidden" name="selected-products" id="selected-products-input" value="">
        </form>

        <div class="card">
        <div class="card-header">
            <h5 class="card-title">Latest Orders</h5>
        </div>
    <div class="card-body">
        <div class="order-list">
            <?php
            $query = "SELECT * FROM orders ORDER BY order_id DESC LIMIT 10";
            $result = mysqli_query($con, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='order'>";
                    echo "<h6>Order ID: #" . $row['order_id'] . "</h6>";
                    
                    $product_query = "SELECT * FROM order_items WHERE order_id = " . $row['order_id'];
                    $product_result = mysqli_query($con, $product_query);
                    
                    while ($product_row = mysqli_fetch_assoc($product_result)) {
                        $product_id = $product_row['item_id'];
                        $quantity = $product_row['quanity'];
                        
                        $product_name_query = "SELECT name FROM menu_items WHERE item_id = " . $product_id;
                        $product_name_result = mysqli_query($con, $product_name_query);
                        $product_name_row = mysqli_fetch_assoc($product_name_result);
                        $product_name = $product_name_row['name'];
                        
                        echo "<p>Name: " . $product_name . "</p>";
                        echo "<p>Quantity: " . $quantity . "</p>";
                    }
                    
                    $order_id = $row['order_id'];
                    $order_query = "SELECT * FROM orders WHERE order_id = " . $order_id;
                    $order_result = mysqli_query($con, $order_query);
                    $order_row = mysqli_fetch_assoc($order_result);
                    $order_date = $order_row['OrderDate'];
                    $total_price = $order_row['totalPrice'];

                    $user_id = $row['user_id'];
                    $user_query = "SELECT * FROM users WHERE user_id = " . $user_id;
                    $user_result = mysqli_query($con, $user_query);
                    $user_row = mysqli_fetch_assoc($user_result);
                    $first_name = $user_row['First name']; 
                    $last_name = $user_row['Last name']; 

                    echo "<p>Ordered By: " . $first_name . " " . $last_name . "</p>";
                    echo "<p>Total Price: $" . $total_price . "</p>";
                    echo "</div>";


                }
            } else {
                echo "<p>No orders found.</p>";
            }

            mysqli_close($con);
            ?>
        </div>
    </div>
    <div class="card-footer">
                <a  class="btn btn-primary view-orders-button">View Orders</a>
                <button class="btn btn-success add-product-button">Add Orders</button>
                <button class="btn btn-danger remove-product-button" style="display:none">Remove Order</button>
            </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   
    <script>
        let arr=[];
        for(let x=0;x< <?php echo $i; ?> ;x++){
            arr[x]=false;
        }
        document.addEventListener('DOMContentLoaded', function() {
            const productDivs = document.querySelectorAll('.product');
            const deleteBtn = document.querySelector('.delete-btn');
            const editBtn = document.querySelector('.edit-btn');

            productDivs.forEach(function(div) {
                div.addEventListener('click', function() {
                    const checkbox = div.querySelector('.product-checkbox');
                    const idSelected =div.querySelector('h7').innerText;
                    arr[idSelected-1]=!arr[idSelected-1];
                    checkbox.checked = !checkbox.checked;

                    const selectedProducts = document.querySelectorAll('.product-checkbox:checked');
                    if (selectedProducts.length > 0) {
                        deleteBtn.style.display = 'inline-block';
                        editBtn.style.display = 'inline-block';
                    } else {
                        deleteBtn.style.display = 'none';
                        editBtn.style.display = 'none';
                    }

                    div.classList.toggle('selected');
                });
            });

            deleteBtn.addEventListener('click', function() {
                const confirmDelete = confirm('Are you sure you want to delete the selected products?');
                if (confirmDelete) {
                    document.getElementById('selected-products-input').value = JSON.stringify(getSelectedProductIds());
                    document.getElementById('selected-products-form').submit();
                }
            });

            editBtn.addEventListener('click', function() {
                const confirmEdit = confirm('Are you sure you want to edit the selected products?');
                if (confirmEdit) {
                    document.getElementById('selected-products-input').value = JSON.stringify(getSelectedProductIds());
                    document.getElementById('selected-products-form').submit();
                }
            });

            function getSelectedProductIds() {
                const selectedIds = [];
                const selectedProducts = document.querySelectorAll('.product-checkbox:checked');
                selectedProducts.forEach(function(checkbox) {
                    selectedIds.push(checkbox.parentNode.querySelector('h7').innerText);
                });
             
                return selectedIds;
            }
            const viewUsersButton = document.querySelector('.view-users-button');
            const viewProductsButton = document.querySelector('.view-products-button');
            const viewOrdersButton = document.querySelector('.view-orders-button');

            viewUsersButton.addEventListener('click', function() {
                toggleSection('Users');
            });
    
            viewProductsButton.addEventListener('click', function() {
                toggleSection('Products');
            });

            viewOrdersButton.addEventListener('click', function() {
                toggleSection('Orders');
            });
            function toggleSection(sectionTitle) {
                const cardHeaders = document.querySelectorAll('.card-header');
                const cardBodies = document.querySelectorAll('.card-body');
    
                for (let i = 0; i < cardHeaders.length; i++) {
                    const header = cardHeaders[i];
                    const body = cardBodies[i];
                    if (header.textContent.includes(sectionTitle)) 
                        body.style.display = body.style.display === 'none' ? 'block' : 'none';
                }
            }
        });
    </script>
</body>
</html>
