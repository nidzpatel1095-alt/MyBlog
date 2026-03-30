<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){ exit; }

$follower_id = $_SESSION['user_id'];
$following_id = $_POST['following_id'];

// Check if already following
$check = $conn->prepare("SELECT * FROM follows WHERE follower_id = ? AND following_id = ?");
$check->bind_param("ii", $follower_id, $following_id);
$check->execute();
$result = $check->get_result();

if($result->num_rows > 0){
    // Unfollow logic
    $del = $conn->prepare("DELETE FROM follows WHERE follower_id = ? AND following_id = ?");
    $del->bind_param("ii", $follower_id, $following_id);
    $del->execute();
} else {
    // Follow logic
    $ins = $conn->prepare("INSERT INTO follows (follower_id, following_id) VALUES (?, ?)");
    $ins->bind_param("ii", $follower_id, $following_id);
    $ins->execute();
}

header("Location: view_profile.php?id=" . $following_id);
?>