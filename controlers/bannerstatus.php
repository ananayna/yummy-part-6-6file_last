<?php
session_start();
include_once './../env.php';
$banner_id = ($_REQUEST['id']);
$query = "UPDATE banners SET status = 0 ";
mysqli_query($conn,$query);
$active_query = "UPDATE banners SET status = 1 WHERE id = '$banner_id' ";
$result = mysqli_query($conn,$active_query);
if($result){
    $_SESSION ['success'] = 'your banner status actived.';
    header("Location: ./../backend/banner_list.php");
}
?>