<?php
session_start();
if(isset($_SESSION['UserL'])){
    header("Location: homepage.php");
    exit;
 }
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="css/register.css?v=<?php echo time(); ?>">
</head>
<body>
<?php include 'navbar.php'; ?>
<?php
$con=mysqli_connect("localhost","root","1234","projectdb");
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname=$_POST["Firstn"];
    $lastname=$_POST["Lastn"];
    $username=$_POST["username"];
    $email=$_POST["email"];
    $password=$_POST["password"];
    $cpassword=$_POST["cpassword"];
    $phone=$_POST["Phone"];
    $birthdate=$_POST["BirthDate"];
    if($password==$cpassword){
        $sql = "INSERT INTO `users` (`First name`, `Last name`, `UserName`, `password`, `e-mail`, `phone`, `birth date`, `user_id`, `Locked`) 
        VALUES ('$firstname', '$lastname', '$username', '$password', '$email', '$phone', '$birthdate', NULL, '0')";

    
        if ($con->query($sql) === TRUE) {
        echo "New record created successfully";
        header("Location: Login.php");
        exit;
        } else {
        echo "Error: " . $sql . "<br>" . $con->error;
        }
    }else{
        echo "<script><alert>Enter Password Correctly </alert></script>";
    }
}
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="Firstn">First name:</label>
    <input type="text" name="Firstn" required>
    <br>
    <label for="Lastn">Last name:</label>
    <input type="text" name="Lastn" required>
    <br>
    <label for="username">Username:</label>
    <input type="text" name="username" required>
    <br>
    <label for="email">Email</label>
    <input type="email" name="email" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" required>
    <br>

    <label for="cpassword">Confirm Password:</label>
    <input type="password" name="cpassword" required>
    <br>    
    <label for="Phone">Phone:</label>
    <input type="number" name="Phone" required>
    <br>

    <label for="BirthDate">BirthDate:</label>
    <input type="date" name="BirthDate" required>
    <br>

    <input type="submit" value="Register">
</form>

</body>
</html>
