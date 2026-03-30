<?php
session_start();
include("../config/db.php");

$current_user = $_SESSION['user_id'];
$q = $_GET['q'] . "%"; // '%' ka matlab hai aage kuch bhi ho sakta hai

$stmt = $conn->prepare("SELECT id, username, profile_pic FROM users WHERE username LIKE ? AND id != ? LIMIT 10");
$stmt->bind_param("si", $q, $current_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dp = $row['profile_pic'] ? $row['profile_pic'] : 'default_user.png';
        echo "
        <a href='view_profile.php?id={$row['id']}' class='user-card'>
            <img src='../uploads/{$dp}'>
            <div class='username'>{$row['username']}</div>
        </a>";
    }
} else {
    echo "<p style='text-align: center; color: red;'>No users found matching '$q'</p>";
}
?>