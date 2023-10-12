<?php
session_start();
include_once './../../env.php';

$oldPassword = $_REQUEST['old_password'];
$password = $_REQUEST['password'];
$confirmPassword = $_REQUEST['confirm_password'];
$hashPassword = password_hash($password, PASSWORD_BCRYPT);
$errors = [];

//Password Validation
if (empty($password)) {
    $errors['passwordError'] = 'Password is required';
} elseif (strlen($password) < 8) {
    $errors['passwordError'] = 'Password can not be less than 8 Character.';
}
//Confirm Password Validation
if ($password !== $confirmPassword) {
    $errors['confirmPasswordError'] = 'Password did not match.';
}

if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
    var_dump($_SESSION['errors']) ;
    header('Location: ./../../backend/profile.php');
} else {
    $authid = $_SESSION['auth']['id'];
    $query = "SELECT pass FROM users WHERE id = '$authid'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    $isValidPassword = password_verify($oldPassword, $data['pass']);
    var_export($isValidPassword);

    if ($isValidPassword) {
        $query = "UPDATE users SET pass='$hashPassword' WHERE id = '$authid'";
        $result = mysqli_query($conn, $query);
        var_dump($result);
        $_SESSION['password_success'] = 'Password Changed successfully.';
        header('Location: ./../../backend/profile.php');

    } else {
        echo 'hello';
        $_SESSION['password_error'] = 'Current Password did not match.';
        header('Location: ./../../backend/profile.php');
    }
}