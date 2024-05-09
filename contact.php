<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>contact</title>
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/login.css">
    
</head>
<body style="margin:10px">
<?php include 'navbar.php'; ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" style="text-align:left;">
    <label>First Name: </label>
    <input type="text" name="firstname" required/><br />
    <label> Last name: </label>
   <input type="text" name="lastname" required/><br />
    <label> E-mail: </label>
   <input type="text" name="mail" required/><br />
    <label> Phone number: </label>
   <input type="text" name="phone" required/><br />
    <label> Why Us: </label>
   <input type="text" name="WhyUs" required/><br />
    <input type="submit" />
</form>

</body>
<?php
function hasAtSymbol($str) {
    return preg_match('/@/', $str) === 1;
}
function have5to20letters($str){
    return preg_match("/^[a-zA-Z]{5,20}$/", $str) ==1;
}
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $errors = [];
    $allcorrect=["firstname"=>false,"lastname"=>false,"mail"=>false,"phone"=>false,"whyus"=>false];
    if (isset($_GET["firstname"]) && !empty($_GET["firstname"])) {
        $firstname = $_GET["firstname"];
        $allcorrect['firstname']=true;
        if (!have5to20letters($firstname)) {
            $errors[] = "First name should at least 5 letters and maximum 20 letters and dont contain numbers.";
        }
    } 

    if (isset($_GET["lastname"]) && !empty($_GET["lastname"])) {
        $lastname = $_GET["lastname"];
        $allcorrect['lastname']=true;
        if (!have5to20letters($lastname)) {
            $errors[] = "Last name should at least 5 letters and maximum 20 letters and dont contain numbers.";
        }
    } 

    
    if (isset($_GET["mail"]) && !empty($_GET["mail"])) {
        $email = $_GET["mail"];
        $allcorrect['mail']=true;
        if (!hasAtSymbol($email)) {
            $errors[] = "Invalid email format.";
        }
    } 

    if (isset($_GET["phone"]) && is_numeric($_GET["phone"])) {
        $allcorrect['phone']=true;
        $phone = $_GET["phone"];
    } 

    if(!empty($_GET["WhyUs"])){
        $allcorrect['whyus']=true;
        $WhyUs=$_GET["WhyUs"];
    }
    $allTrue = true; 

    foreach ($allcorrect as $key => $value) {
        if (!$value) {
            $allTrue = false; 
            break;
        }
    }
    if (empty($errors)&&$allTrue) {
        echo "<center>Welcome</center> <br /> First Name -" . $firstname . "<br /> Last Name -" . $lastname . "<br />";
        echo "e-mail- " . $email . "<br />";
        echo "phone number- " . $phone . "<br />";
        echo "Why Us- " .$WhyUs;
        exit();
    } else {
        $str="";
        foreach ($errors as $error) {
            $str.=$error . "<br />";
        }
        if($str !=null && !empty($str))
            echo "<script>alert('" . $str . "');</script>";
    }
}
?>
</html>