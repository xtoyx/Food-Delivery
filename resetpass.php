<?php
    session_start();

    $con=mysqli_connect("localhost","root","1234","projectdb");
    if (mysqli_connect_errno())
    {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    $result = mysqli_query($con,"SELECT * FROM users");
    $array = array();
    $i=0;
    $arr1=array();
    while ($row = mysqli_fetch_array($result)) {
        $array[$i]["username"]=$row['2'];
        $array[$i]["password"]=$row['3'];
        $array[$i]["email"]=$row['4'];
        $array[$i]["user_id"]=$row['7'];
        $array[$i]["Locked"]=$row['8'];
        $array[$i]["chan_bool"]=$row['11'];
        $arr1[$row['2']]=0;
        $i++;
    }
    $users=$array;
    $error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST["username"];
    $input_password = $_POST["password"];
    $input_cpassword = $_POST["Cpassword"];

    $user_found = false;
    foreach ($users as $user) {
        if ($user["username"] == $input_username) {
            $user_found = true;
            if($user["Locked"]==0 && $user["chan_bool"] == 1)
                if ($user["password"] != $input_password && $input_cpassword == $input_password) {
                    $sql = "UPDATE users SET Locked='0' ,temp_password='',chan_bool='0',password='".$input_password."' WHERE Username='" . $user["username"] . "'";
                    if ($con->query($sql) === TRUE) {
                    echo "Record updated successfully";
                    } else {
                    echo "Error updating record: " . $con->error;
                    }
                    header("Location: homepage.php");
                    session_destroy();
                    exit;
                } else if($user["password"] == $input_password){
                    $error="Cant Set Your Old Password";
                } else {
                    $error="Confirm password = password";
                }
            else {
                $error="Your Account have been not Locked";
            };
            break; 
        }
    }

    if (!$user_found) {
        $error = "User not found.";
    }
    }
    mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="css/login.css?v=<?php echo time(); ?>">
</head>
<body>
<?php include 'navbar.php'; ?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" name="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" required>
    <br>
    <label for="Cpassword">Confirm Password:</label>
    <input type="password" name="Cpassword" required>
    <br>
    <input type="submit" value="Send">
</form>
    <?php if ((!$error || $error != "")) { ?>
            <p class="error"><?php echo $error; ?></p>
    <?php } ?>
</body>
</html>

