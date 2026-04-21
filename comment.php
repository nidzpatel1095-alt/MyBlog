<?php
session_start();
include("config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];
$comment = $_POST['comment'];

$stmt = $conn->prepare("INSERT INTO comments (user_id, post_id, comment) VALUES (?,?,?)");
$stmt->bind_param("iis", $user_id, $post_id, $comment);
$stmt->execute();

header("Location: index.php");
exit();
?>