<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

if(isset($_POST['update_profile'])){
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $profile_pic = $user['profile_pic'];

    // Profile Pic Upload logic
    if(!empty($_FILES['p_pic']['name'])){
        $profile_pic = time() . "_" . $_FILES['p_pic']['name'];
        move_uploaded_file($_FILES['p_pic']['tmp_name'], "../uploads/" . $profile_pic);
    }

    $update = $conn->prepare("UPDATE users SET bio = ?, profile_pic = ? WHERE id = ?");
    $update->bind_param("ssi", $bio, $profile_pic, $user_id);
    
    if($update->execute()){
        header("Location: dashboard.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="navbar"><h2>MyBlog</h2><a href="dashboard.php">Back</a></div>

    <div class="login-container">
        <h2>Edit Profile 👤</h2>
        <form method="POST" enctype="multipart/form-data">
            <label style="display:block; text-align:left;">Bio:</label>
            <textarea name="bio" placeholder="Write about yourself..."><?php echo $user['bio']; ?></textarea>
            
            <label style="display:block; text-align:left; margin-top:10px;">Change Profile Photo:</label>
            <input type="file" name="p_pic" accept="image/*">
            
            <button name="update_profile" style="margin-top:20px;">Save Changes</button>
        </form>
    </div>
</body>
</html>