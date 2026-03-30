<?php
include("../includes/header.php");
include("../config/db.php");

$current_user = $_SESSION['user_id'];
$target_user_id = $_GET['id'];

// Khud ki profile pe redirect kar do agar apni id hai
if($current_user == $target_user_id){
    header("Location: dashboard.php");
}

// User Info Fetch
$user = $conn->query("SELECT * FROM users WHERE id = $target_user_id")->fetch_assoc();

// Check if already following
$check = $conn->query("SELECT * FROM follows WHERE follower_id = $current_user AND following_id = $target_user_id");
$is_following = $check->num_rows > 0;
?>

<div class="profile-header" style="display: flex; align-items: center; max-width: 600px; margin: 40px auto; padding: 20px;">
    <img src="../uploads/<?php echo $user['profile_pic'] ? $user['profile_pic'] : 'default_user.png'; ?>" 
         style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-right: 40px;">
    
    <div class="profile-info">
        <h2><?php echo $user['username']; ?></h2>
        
        <form action="follow_action.php" method="POST" style="display: inline;">
            <input type="hidden" name="following_id" value="<?php echo $target_user_id; ?>">
            <button type="submit" style="background: <?php echo $is_following ? '#ddd' : '#3897f0'; ?>; 
                                          color: <?php echo $is_following ? 'black' : 'white'; ?>; 
                                          padding: 5px 20px; border-radius: 5px; cursor: pointer; border: none; font-weight: bold;">
                <?php echo $is_following ? 'Unfollow' : 'Follow'; ?>
            </button>
        </form>

        <p><?php echo $user['bio']; ?></p>
    </div>
</div>

<div class="post-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; max-width: 800px; margin: auto; border-top: 1px solid #ddd; padding-top: 20px;">
    <?php
    $posts = $conn->query("SELECT * FROM posts WHERE user_id = $target_user_id ORDER BY id DESC");
    while($p = $posts->fetch_assoc()){
        echo "<div class='grid-item'><img src='../uploads/{$p['image']}' style='width: 100%; height: 200px; object-fit: cover;'></div>";
    }
    ?>
</div>

<?php include("../includes/footer.php"); ?>