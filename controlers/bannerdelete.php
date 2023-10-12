<?php
session_start();
include_once './../env.php';
$banner_id = ($_REQUEST['id']);
            // for count
$select_query ="SELECT * FROM banners";
$result = mysqli_query($conn,$select_query);
$add = mysqli_fetch_all($result, 1);
        // for image delete uploads file
$select_query1 ="SELECT * FROM banners WHERE id= '$banner_id'";
$result1 = mysqli_query($conn,$select_query1);
$data = mysqli_fetch_assoc($result1);
$image_path ='./../uploads/'.$data['image'];

if(count($add) > 1){
    if(file_exists($image_path)){
        unlink($image_path);
    }
    $query = "DELETE FROM banners WHERE id= '$banner_id'";
    $result = mysqli_query($conn, $query);
    if($result){
        header("Location: ./../backend/banner_list.php");
    }
}else{
    header("Location: ./../backend/banner_list.php");
}

?>