<?php

session_start();
include_once './../../env.php';
$email = trim($_REQUEST['email']);
$password = $_REQUEST['pass'];
$errors = [];



//Email Validation
if (empty($email)) {
    $errors['emailError'] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['emailError'] = 'Invalid Email Address';
}

//Password Validation
if (empty($password )) {
    $errors['passError'] = 'Password is required';
} elseif (strlen($password ) < 8) {
    $errors['passError'] = 'Password can not be less than 8 Character.';
}



if (count($errors)>0){
    $_SESSION = $errors;
    header('Location: ./../../backend/login.php');
    print_r ($_SESSION);
}else {
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $authuserdata = mysqli_fetch_assoc($result);
    

    if(mysqli_num_rows($result) > 0){
      $isvalidpassword = password_verify($password, $authuserdata['pass']);

      
      if($isvalidpassword){
        $_SESSION['auth']= $authuserdata;
        header("Location: ./../../backend/dashboard.php");
      }else{
        $_SESSION['pass_error']='Enter correct email.';
        header("Location: ./../../backend/login.php");     
        }

    }else{
        $_SESSION['email_error']='Enter correct email.';
        header("Location: ./../../backend/login.php");
    }

}
?>