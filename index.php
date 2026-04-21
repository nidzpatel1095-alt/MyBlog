<?php
include("includes/header.php");
include("config/db.php");

// Category filter
$cat = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

// Fetch posts with username and total likes
$sql = "SELECT posts.*, users.username,
        (SELECT COUNT(*) FROM likes WHERE post_id = posts.id) AS total_likes
        FROM posts
        JOIN users ON posts.user_id = users.id";

if ($cat != '') {
    $sql .= " WHERE posts.category = '$cat'";
}

$sql .= " ORDER BY posts.id DESC";
$result = $conn->query($sql);
?>

<style>
    .category-bar {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding: 15px;
        max-width: 600px;
        margin: auto;
        scrollbar-width: none;
    }

    .cat-btn {
        background: white;
        border: 1px solid #ddd;
        padding: 5px 15px;
        border-radius: 20px;
        text-decoration: none;
        color: #333;
        white-space: nowrap;
        font-size: 14px;
        transition: 0.3s;
    }

    .cat-btn:hover,
    .cat-btn.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .guest-msg {
        max-width: 500px;
        margin: 20px auto;
        background: #fff3cd;
        color: #856404;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #ffeeba;
        text-align: center;
        font-size: 14px;
    }

    .like-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 15px;
    }

    .login-note {
        text-decoration: none;
        color: #667eea;
        font-weight: bold;
        font-size: 14px;
    }
</style>

<?php if (!isset($_SESSION['user_id'])) { ?>
    <div class="guest-msg">
        You are browsing as Guest. To like, comment, or create posts, please login or register.
    </div>
<?php } ?>

<div class="category-bar">
    <a href="index.php" class="cat-btn <?php echo $cat == '' ? 'active' : ''; ?>">All</a>
    <a href="index.php?category=Food" class="cat-btn <?php echo $cat == 'Food' ? 'active' : ''; ?>">Food 🍕</a>
    <a href="index.php?category=Travel" class="cat-btn <?php echo $cat == 'Travel' ? 'active' : ''; ?>">Travel ✈️</a>
    <a href="index.php?category=Tech" class="cat-btn <?php echo $cat == 'Tech' ? 'active' : ''; ?>">Tech 💻</a>
    <a href="index.php?category=Fashion" class="cat-btn <?php echo $cat == 'Fashion' ? 'active' : ''; ?>">Fashion 👗</a>
    <a href="index.php?category=Fitness" class="cat-btn <?php echo $cat == 'Fitness' ? 'active' : ''; ?>">Fitness 💪</a>
</div>

<div class="feed-container">
    <?php if ($result && $result->num_rows > 0) { ?>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="post-card">
                <div class="post-header">
                    👤 <b><?php echo htmlspecialchars($row['username']); ?></b>
                    <span style="float:right; font-size:12px; color:#888;">
                        #<?php echo htmlspecialchars($row['category']); ?>
                    </span>
                </div>

                <img class="post-img" src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Post Image">

                <div class="post-actions">
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <button class="like-btn" data-id="<?php echo $row['id']; ?>">
                            ❤️ <span class="like-count-<?php echo $row['id']; ?>"><?php echo $row['total_likes']; ?></span> Likes
                        </button>
                    <?php } else { ?>
                        <a href="auth/login.php" class="login-note">
                            ❤️ <?php echo $row['total_likes']; ?> Likes (Login to interact)
                        </a>
                    <?php } ?>
                </div>

                <div class="post-content">
                    <b><?php echo htmlspecialchars($row['title']); ?></b><br>
                    <b><?php echo htmlspecialchars($row['username']); ?></b>
                    <?php echo htmlspecialchars($row['content']); ?>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="post-card" style="padding: 20px; text-align:center;">
            <p>No posts available.</p>
        </div>
    <?php } ?>
</div>

<?php if (isset($_SESSION['user_id'])) { ?>
    <script src="assets/js/script.js"></script>
<?php } ?>

<?php include("includes/footer.php"); ?>