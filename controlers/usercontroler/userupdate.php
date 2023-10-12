<?php

session_start();
include_once './../../env.php';
$fname = trim($_REQUEST['fname']);
$lname = trim($_REQUEST['lname']);
$email = trim($_REQUEST['email']);
$avatar = $_FILES['avatar'];
$avatarextention = pathinfo($avatar['name'], PATHINFO_EXTENSION);
$expectextention = ['jpg','png','jpeg'];

$errors = [];

//First Name Validation
if (empty($fname)) {
    $errors['fnameError'] = 'First name is required';
} elseif (strlen($fname) > 20) {
    echo $errors['fnameError'] = 'First name can not be more than 20 Character.';
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


// image validation
if($avatar['size'] > 0){
        if(!in_array($avatarextention,$expectextention)){
            $errors['avatar']= "just use jpg, jpeg, png";
        }elseif($avatar['size']>5000000){
             $errors['avatar']= "file size is in 5mb";
        }
}




 if (count($errors)>0){
    $_SESSION['errors'] = $errors;
    header("Location: ./../../backend/profile.php");
    
}
else{
    $authId = $_SESSION['auth']['id'];
    $select_query1 ="SELECT * FROM users WHERE id= '$authId'";
    $result1 = mysqli_query($conn,$select_query1);
    $data = mysqli_fetch_assoc($result1);
    $image_path ='./../../uploads/'.$data['avatar'];

    $avatar_name = uniqid().'.'.$avatarextention;
    
    if($avatar['size'] > 0){
        if(file_exists($image_path)){
            unlink($image_path);
        }
        $query = "UPDATE users SET fname='$fname',lname='$lname',email='$email', avatar='$avatar_name' WHERE id='$authId'";
    }else{
        $query = "UPDATE users SET fname='$fname',lname='$lname',email='$email' WHERE id='$authId'";

    }
    $result = mysqli_query($conn, $query);

    if($result){
        move_uploaded_file($avatar['tmp_name'], './../../uploads/'.$avatar_name);
        $_SESSION["success"]="Account Update Successfully.";
        if($avatar['size'] > 0){
            $_SESSION['auth']['avatar']= $avatar_name;
        }
        $_SESSION['auth']['fname']= $fname;
        $_SESSION['auth']['lname']= $lname;
        $_SESSION['auth']['email']= $email;

        header('Location: ./../../backend/profile.php');
    }else{
        $_SESSION["failed"]="Account update Failed.";
        header('Location: ./../../backend/profile.php');
    }
}

?>