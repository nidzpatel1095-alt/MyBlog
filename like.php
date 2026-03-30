<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){ exit("login_required"); }

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];

// Check if already liked
$check = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND post_id = ?");
$check->bind_param("ii", $user_id, $post_id);
$check->execute();
$result = $check->get_result();

if($result->num_rows > 0) {
    // Already liked? Then UNLIKE it
    $delete = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
    $delete->bind_param("ii", $user_id, $post_id);
    $delete->execute();
    echo "unliked";
} else {
    // Not liked? Then LIKE it
    $insert = $conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
    $insert->bind_param("ii", $user_id, $post_id);
    $insert->execute();
    echo "liked";
}
?>