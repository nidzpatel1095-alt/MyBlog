<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){ exit; }

// User Delete logic
if(isset($_GET['del_user'])){
    $uid = $_GET['del_user'];
    // Database ne "ON DELETE CASCADE" laga rakha hai, toh posts apne aap delete ho jayengi
    $conn->query("DELETE FROM users WHERE id = $uid");
    header("Location: dashboard.php?msg=UserDeleted");
}

// Post Delete logic
if(isset($_GET['del_post'])){
    $pid = $_GET['del_post'];
    $conn->query("DELETE FROM posts WHERE id = $pid");
    header("Location: dashboard.php?msg=PostRemoved");
}
?>