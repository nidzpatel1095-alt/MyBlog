<?php
// 1. Header aur Config connect karo (Path dhyan se dekhna ../ use kiya hai)
include("../includes/header.php"); 
include("../config/db.php");

// 2. Login Check
if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 3. User ki information fetch karo (Bio aur DP ke liye)
$user_query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user = $user_query->get_result()->fetch_assoc();

// 4. User ki saari posts fetch karo
$posts_result = $conn->query("SELECT * FROM posts WHERE user_id = $user_id ORDER BY id DESC");
$post_count = $posts_result->num_rows;
?>

<style>
    .profile-header { display: flex; align-items: center; max-width: 600px; margin: 40px auto; padding: 20px; }
    .profile-img-container { position: relative; width: 150px; height: 150px; margin-right: 40px; }
    .profile-img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 2px solid #ddd; }
    .profile-info h2 { margin: 0; display: inline-block; font-size: 28px; font-weight: 300; }
    .edit-btn { text-decoration: none; padding: 5px 15px; border: 1px solid #ddd; border-radius: 5px; color: black; font-size: 14px; margin-left: 20px; font-weight: bold; }
    .stats { margin: 15px 0; font-size: 16px; }
    .bio-name { font-weight: bold; display: block; }
    .post-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; max-width: 800px; margin: 40px auto; padding: 10px; border-top: 1px solid #ddd; }
    .grid-item img { width: 100%; height: 250px; object-fit: cover; cursor: pointer; border-radius: 5px; transition: 0.3s; }
    .grid-item img:hover { filter: brightness(0.7); }
</style>

<div class="profile-header">
    <div class="profile-img-container">
        <img src="../uploads/<?php echo $user['profile_pic'] ? $user['profile_pic'] : 'default_user.png'; ?>" class="profile-img">
    </div>
    
    <div class="profile-info">
        <h2><?php echo $user['username']; ?></h2>
        <a href="edit_profile.php" class="edit-btn">Edit Profile</a>
        
        <div class="stats">
            <b><?php echo $post_count; ?></b> posts
        </div>
        
        <span class="bio-name"><?php echo $user['username']; ?></span>
        <p><?php echo $user['bio'] ? $user['bio'] : "No bio yet. Click Edit Profile to add one! ✨"; ?></p>
    </div>
</div>

<div class="post-grid">
    <?php if($post_count > 0){ ?>
        <?php while($row = $posts_result->fetch_assoc()){ ?>
            <div class="grid-item">
                <img src="../uploads/<?php echo $row['image']; ?>" title="<?php echo $row['title']; ?>">
                <div style="text-align: center; margin-top: 5px;">
                    <a href="edit_post.php?id=<?php echo $row['id']; ?>" style="font-size: 12px; color: blue;">Edit</a> | 
                    <a href="delete_post.php?id=<?php echo $row['id']; ?>" style="font-size: 12px; color: red;" onclick="return confirm('Pakka delete karna hai?')">Delete</a>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div style="grid-column: span 3; text-align: center; padding: 50px; color: #888;">
            <p>You haven't posted anything yet.</p>
            <a href="create_post.php" style="color: #667eea; font-weight: bold;">Create your first post</a>
        </div>
    <?php } ?>
</div>

<?php 
// 5. Footer include karo
include("../includes/footer.php"); 
?>