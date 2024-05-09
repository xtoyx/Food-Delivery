<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../Login.php");
    exit;
}

if(isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $con = mysqli_connect("localhost", "root", "1234", "projectdb");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $query = "SELECT * FROM users WHERE user_id = '$user_id'";
    $result = mysqli_query($con, $query);
    $user = mysqli_fetch_assoc($result);

    mysqli_close($con);
} else {
    echo "User ID not provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/navbar.css?v=<?php echo time(); ?>">

</head>
<body>
<?php include"../navbar_admin.php"; ?>
    <div class="container">
        <h1>User Details</h1>
        <div>
           <!-- bonus -->
            <img src="../<?php echo $user['img']; ?>" alt="User Image">
            <h2><?php echo $user['Username']; ?></h2>
            <p>First Name: <?php echo $user['First name']; ?></p>
            <p>Last Name: <?php echo $user['Last name']; ?></p>
            <p>Email: <?php echo $user['e-mail']; ?></p>
            <p>Phone: <?php echo $user['phone']; ?></p>
            <p>Birth Date: <?php echo $user['birth date']; ?></p>
            <p>Password: <?php echo $user['password']; ?></p>
            <p>Locked: <?php echo $user['Locked']; ?></p>

            <p>Login Attempt : <?php echo $user['login_attempt']; ?></p>
            <p>Failed Attempt: <?php echo $user['failed_atempt']; ?></p>
            <p>Failed Attempt Date: <?php echo $user['atempt_Date']; ?></p>
            <p>Last Login: <?php echo $user['last_Login']; ?></p>
            <p>Login Status: 
                <?php 
                if($user['login_status']==1) echo "true";
                else echo "false";
                ?>
            </p>

            <p>temp_password: <?php echo $user['temp_password']; ?></p>
            <p>chan_bool: <?php echo $user['chan_bool']; ?></p>

            <!-- * -->
        </div>
    </div>
</body>
</html>
