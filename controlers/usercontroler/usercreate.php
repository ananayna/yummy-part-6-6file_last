<?php

session_start();
include_once './../../env.php';
$fname = trim($_REQUEST['fname']);
$lname = trim($_REQUEST['lname']);
$email = trim($_REQUEST['email']);
$pass = $_REQUEST['pass'];
$cpass = $_REQUEST['cpass'];
$hashPass = password_hash($pass, PASSWORD_BCRYPT);
$errors = [];



//First Name Validation
if (empty($fname)) {
    $errors['fnameError'] = 'First name is required';
} elseif (strlen($fname) > 20) {
    $errors['fnameError'] = 'First name can not be more than 20 Character.';
}


//Last Name Validation
if (empty($lname)) {
    $errors['lnameError'] = 'Last name is required';
} elseif (strlen($lname) > 20) {
    $errors['lnameError'] = 'Last name can not be more than 20 Character.';
}


//Email Validation
if (empty($email)) {
    $errors['emailError'] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['emailError'] = 'Invalid Email Address';
}

//Password Validation
if (empty($pass)) {
    $errors['passError'] = 'Password is required';
} elseif (strlen($pass) < 8) {
    $errors['passError'] = 'Password can not be less than 8 Character.';
}

//Confirm Password Validation
if ($pass !== $cpass) {
    $errors['cpassError'] = 'Password did not match.';
}


if (count($errors)>0){
    $_SESSION = $errors;
    header('Location: ./../../backend/register.php');
    print_r ($_SESSION);
}else{
    $query = "INSERT INTO users(fname, lname, email, pass) VALUES ('$fname','$lname','$email','$hashPass')";
    $result = mysqli_query($conn, $query);

    if($result){
        $_SESSION["success"]="Account Created Successfully. Please login your account.";
        header('Location: ./../../backend/login.php');
    }else{
        $_SESSION["failed"]="Account Creation Failed.";
        header('Location: ./../../backend/register.php');
    }
}

?>