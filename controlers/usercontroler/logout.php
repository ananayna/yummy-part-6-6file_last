<?php
session_start();
header('Location: ./../../backend/login.php');
session_unset();
?>