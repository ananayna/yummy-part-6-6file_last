<?php
session_start();
include_once './../env.php';
$title = trim($_REQUEST['title']);
$description = trim($_REQUEST['description']);
$cta_text = trim($_REQUEST['cta_text']);
$cta_link = trim($_REQUEST['cta_link']);
$video_link = trim($_REQUEST['video_link']);
$image = $_FILES['image'];
$avatarextention = pathinfo($image['name'], PATHINFO_EXTENSION);
$expectextention = ['jpg','png','jpeg','webp'];
$image_size = $image['size'];
$update_id = $_REQUEST['id'];
$errors=[];

//title Validation
if (empty($title)) {
    $errors['title_error'] = 'Title is required';
} elseif (strlen($title) > 100) {
    $errors['title_error'] = 'Title can not be more than 100 Character.';
}

//description Validation
if (empty($description)) {
    $errors['description_error'] = 'description is required';
} elseif (strlen($description) > 200) {
    $errors['description_error'] = 'description can not be more than 200 Character.';
}

//cta_text Validation
if (empty($cta_text)) {
    $errors['cta_text_error'] = 'cta text is required';
} elseif (strlen($cta_text) > 50) {
    $errors['cta_text_error'] = 'cta text can not be more than 50 Character.';
}

//cta_text Validation
if (empty($cta_link)) {
    $errors['cta_link_error'] = 'cta link is required';
}

//cta_text Validation
if (empty($video_link)) {
    $errors['video_link_error'] = 'video link is required';
}


// image validation
if($image_size > 0){
    if(!in_array($avatarextention,$expectextention)){
        $errors['image_error']= "just use jpg, jpeg, png";
    }elseif($image_size > 5000000){
         $errors['image_error']= "file size is in 5mb";
    }
}
var_dump($errors);

if(count($errors) > 0){
    $_SESSION['errors'] = $errors;
    header("Location: ./../backend/banner_edit.php?id=$update_id");
}else{
    $select_query1 ="SELECT * FROM banners WHERE id= '$update_id'";
    $result1 = mysqli_query($conn,$select_query1);
    $data = mysqli_fetch_assoc($result1);
    $image_path ='./../uploads/'.$data['image'];

    $image_name = uniqid().'.'.$avatarextention;
    if($image_size > 0){
        if(file_exists($image_path)){
            unlink($image_path);
        }
        $query = "UPDATE banners SET title='$title',description='$description',cta_text='$cta_text',
        cta_link='$cta_link',video_link='$video_link',image='$image_name' WHERE id= '$update_id'";
    }else{
        $query = "UPDATE banners SET title='$title',description='$description',cta_text='$cta_text',
        cta_link='$cta_link',video_link='$video_link' WHERE id= '$update_id'";

    }
    $result = mysqli_query($conn,$query);
    if($result){
        move_uploaded_file($image['tmp_name'], './../uploads/'.$image_name );
        $_SESSION['success'] = 'Benner insert successfully';
        
        header("Location: ./../backend/banner_edit.php?id=$update_id");
    }else{
        $_SESSION['failed'] = 'Benner is not insert';
        header("Location: ./../backend/banner_edit.php?id=$update_id");
    }
}
