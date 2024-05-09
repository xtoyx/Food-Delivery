<?php
session_start();

unset($_SESSION['admin']);
//bonus
$con = mysqli_connect("localhost", "root", "1234", "projectdb");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$sql = "UPDATE users SET login_status=0 WHERE user_id='" . $_SESSION['UserL']['id'] . "'";
if ($con->query($sql) === TRUE) {
echo "Record updated successfully";
} else {
echo "Error updating record: " . $con->error;
}
//*
unset($_SESSION['UserL']);

header("Location: login.php");
exit();
?>
