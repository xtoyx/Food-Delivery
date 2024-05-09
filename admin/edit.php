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

$selectedProducts = isset($_SESSION['selected_products']) ? $_SESSION['selected_products'] : [];
$productIds = implode(',', array_map('intval', $selectedProducts));

$products_sql = "SELECT * FROM menu_items WHERE item_id IN ($productIds)";
$products_result = $con->query($products_sql);

if (!$products_result) {
    echo "Error fetching product data: " . $con->error;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editedItems'])) {
    foreach ($_POST['editedItems'] as $editedItem) {
        $Item = json_decode($editedItem, true);
        foreach ($Item as $innerItem) {
            if (isset($innerItem['itemId'])) {
                $Item_id=$innerItem['itemId'];
                $Item_NValue=$innerItem['newValue'];
                $Item_Wh=$innerItem['changedField'];
                $Item_Wh=str_replace("[]","",$Item_Wh);
                $sql = "UPDATE menu_items SET $Item_Wh='".$Item_NValue."' WHERE item_id='" .  $Item_id . "'";
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




$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Products</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/navbar.css?v=<?php echo time(); ?>">

</head>
<body>
<?php include"../navbar_admin.php"; ?>
    <div class="container">
        <h1 class="mt-4">Edit Products</h1>
        <div class="table-responsive">
            <form id="editForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Image Path</th>
                            <th style="display: none;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $products_result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><input type='hidden' name='item_id[]' value='" . $row["item_id"] . "'>" . $row["item_id"] . "</td>";
                            echo "<td><img src='../" . $row["img"] . "' alt='" . $row["name"] . "' class='img-thumbnail' style='width: 100px; height: 100px;'></td>";
                            echo "<td><input type='text' name='name[]' value='" . $row["name"] . "' data-old-value='" . $row["name"] . "'></td>";
                            echo "<td><input type='text' name='description[]' value='" . $row["description"] . "' data-old-value='" . $row["description"] . "'></td>";
                            echo "<td><input type='text' name='price[]' value='" . $row["price"] . "' data-old-value='" . $row["price"] . "'></td>";
                            echo "<td><input type='text' name='quantity[]' value='" . $row["quantity"] . "' data-old-value='" . $row["quantity"] . "'></td>";
                            echo "<td><input type='text' name='img_path[]' value='../" . $row["img"] . "' data-old-value='../" . $row["img"] . "'></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <input type="hidden" id="editedItemsInput" name="editedItems[]">
                <button type="button" id="saveChanges" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
    <script>
      document.getElementById("saveChanges").addEventListener("click", function() {
    var editedItems = [];
    var inputs = document.querySelectorAll("#editForm input[type='text']");
    inputs.forEach(function(input) {
        var oldValue = input.getAttribute('data-old-value');
        var newValue = input.value;
        if (oldValue !== newValue) {
            var itemName = input.closest('tr').querySelector('td:nth-child(3)').innerText;
            var itemId = input.closest('tr').querySelector('td:nth-child(1)').innerText;
            editedItems.push({
                "itemId": itemId,
                "itemName": itemName,
                "oldValue": oldValue,
                "newValue": newValue,
                "changedField": input.name
            });
        }
    });
    if (editedItems.length > 0) {
        console.log("Edited Items:\n" + JSON.stringify(editedItems));
        document.getElementById("editedItemsInput").value = JSON.stringify(editedItems);
        document.getElementById("editForm").submit();
    } else {
        alert("No items were edited.");
    }
});



    </script>
</body>
</html>
