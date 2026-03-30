<?php
include("includes/header.php");
include("config/db.php");

if(!isset($_SESSION['user_id'])){ header("Location: auth/login.php"); exit(); }

// Filter logic
$cat = isset($_GET['category']) ? $_GET['category'] : '';
$sql = "SELECT posts.*, users.username, (SELECT COUNT(*) FROM likes WHERE post_id = posts.id) AS total_likes 
        FROM posts JOIN users ON posts.user_id = users.id ";

if($cat != '') {
    $sql .= " WHERE posts.category = '$cat' ";
}
$sql .= " ORDER BY posts.id DESC";
$result = $conn->query($sql);
?>

<style>
    .category-bar { display: flex; gap: 10px; overflow-x: auto; padding: 15px; max-width: 600px; margin: auto; scrollbar-width: none; }
    .cat-btn { background: white; border: 1px solid #ddd; padding: 5px 15px; border-radius: 20px; text-decoration: none; color: #333; white-space: nowrap; font-size: 14px; transition: 0.3s; }
    .cat-btn:hover, .cat-btn.active { background: #667eea; color: white; border-color: #667eea; }
</style>

<div class="category-bar">
    <a href="index.php" class="cat-btn <?php echo $cat==''?'active':''; ?>">All</a>
    <a href="index.php?category=Food" class="cat-btn <?php echo $cat=='Food'?'active':''; ?>">Food 🍕</a>
    <a href="index.php?category=Travel" class="cat-btn <?php echo $cat=='Travel'?'active':''; ?>">Travel ✈️</a>
    <a href="index.php?category=Tech" class="cat-btn <?php echo $cat=='Tech'?'active':''; ?>">Tech 💻</a>
    <a href="index.php?category=Fashion" class="cat-btn <?php echo $cat=='Fashion'?'active':''; ?>">Fashion 👗</a>
    <a href="index.php?category=Fitness" class="cat-btn <?php echo $cat=='Fitness'?'active':''; ?>">Fitness 💪</a>
</div>

<div class="feed-container">
    <?php while($row = $result->fetch_assoc()){ ?>
    <div class="post-card">
        <div class="post-header">
            👤 <b><?php echo $row['username']; ?></b> 
            <span style="float:right; font-size:12px; color:#888;">#<?php echo $row['category']; ?></span>
        </div>
        <img class="post-img" src="uploads/<?php echo $row['image']; ?>">
        <div class="post-actions">
            <button class="like-btn" data-id="<?php echo $row['id']; ?>"> ❤️ <?php echo $row['total_likes']; ?> Likes</button>
        </div>
        <div class="post-content">
            <b><?php echo $row['username']; ?></b> <?php echo $row['content']; ?>
        </div>
    </div>
    <?php } ?>
</div>

<script src="assets/js/script.js"></script>
<?php include("includes/footer.php"); ?>