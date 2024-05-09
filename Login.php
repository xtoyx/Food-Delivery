<?php
session_start();
if(isset($_SESSION['UserL'])){
    header("Location: homepage.php");
    exit;
 }
    $con=mysqli_connect("localhost","root","1234","projectdb");
    if (mysqli_connect_errno())
    {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    $result = mysqli_query($con,"SELECT * FROM users");

    function generateRandomPassword($length) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_';
    
        $charsLength = strlen($chars);
    
        $password = '';
    
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, $charsLength - 1)];
        }
    
        return $password;
    }

    $array = array();
    $i=0;
    $arr1=array();
    while ($row = mysqli_fetch_array($result)) {
        $array[$i]["fname"]=$row['0'];
        $array[$i]["lname"]=$row['1'];
        $array[$i]["username"]=$row['2'];
        $array[$i]["password"]=$row['3'];
        $array[$i]["email"]=$row['4'];
        $array[$i]["phone"]=$row['5'];
        $array[$i]["birthdate"]=$row['6'];
        $array[$i]["user_id"]=$row['7'];
        $array[$i]["Locked"]=$row['8'];
        $arr1[$row['2']]=0;
        $i++;
    }
    $users=$array;
   

    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = $arr1;
    }
    $error = "";
    $error_text="";
    $redcolornow=false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST["username"];
    $input_password = $_POST["password"];

    $user_found = false;
        foreach ($users as $user) {
            if ($user["username"] == $input_username) {
                $user_found = true;
                
                //bonus
                $sql = "UPDATE users SET login_attempt=login_attempt+1 WHERE Username='" . $user["username"] . "'";
                if ($con->query($sql) === TRUE) {
                } else {
                echo "Error updating record: " . $con->error;
                }
                //*

                if($user["Locked"]!=1)
                    if ($user["password"] == $input_password) {
                        $_SESSION['login_attempts'][$user["username"]]=0;
                        if (!isset($_SESSION['UserL'])) {
                            $_SESSION['UserL']=array('username' => $user["username"], 'id' => $user["user_id"]);
                        }
                         //bonus
                         $sql = "UPDATE users SET login_status=1,last_Login=NOW() WHERE user_id='" . $user["user_id"] . "'";
                         if ($con->query($sql) === TRUE) {
                         echo "Record updated successfully";
                         } else {
                         echo "Error updating record: " . $con->error;
                         }
                         //*

                        if($input_username=="yousef")
                            if (!isset($_SESSION['admin'])) {
                                $_SESSION['admin'] = true;
                                header("Location: admin/index.php");
                                exit;
                        }
                       
                        header("Location: homepage.php");
                        exit;
                    } else {
                        $_SESSION['login_attempts'][$user["username"]]++;
                        
                        //bonus
                        $sql = "UPDATE users SET failed_atempt=failed_atempt+1 WHERE user_id='" . $user["user_id"] . "'";
                        if ($con->query($sql) === TRUE) {
                        echo "Record updated successfully";
                        } else {
                        echo "Error updating record: " . $con->error;
                        }
                        //*

                        if ($_SESSION['login_attempts'][$user["username"]] >= 3) {
                            $sql = "UPDATE users SET Locked='1' ,atempt_Date=NOW(),temp_password='".generateRandomPassword(10)."' WHERE Username='" . $user["username"] . "'";
                            if ($con->query($sql) === TRUE) {
                            echo "Record updated successfully";
                            } else {
                            echo "Error updating record: " . $con->error;
                            }
                            include 'smtp.php';
                            $redcolornow=true;
                            $error_text="You are Locked.";
                        } else {
                            $redcolornow=false;
                            $error_text="Wrong username or password. you have another " . (3 - $_SESSION['login_attempts'][$user["username"]]) . " attempts.";
                        }
                    }
                else {
                    $error="Your Account have been Locked";
                }
                break; 
            }
        }

            if (!$user_found) {
                $error = "User not found.";
            }
            if (!$user_found || !empty($error)) {
                echo '<script>alert("'.$error.' Please try again.");</script>';
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
    <input type="submit" value="Send">
    <a href="forgpass.php">Forget Password?</a>
</form>
    <?php if ((!$error_text || $error_text != "")&&!$redcolornow) { ?>
            <p class="error"><?php echo $error_text; ?></p>
    <?php } ?>
    <?php if ($redcolornow) { ?>
            <p class="error red"><?php echo $error_text; ?></p>
    <?php } ?>
</body>
</html>

