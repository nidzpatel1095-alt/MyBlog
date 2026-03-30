<?php
session_start();
include("../config/db.php");

// Security: Sirf Admin hi dekh sakta hai
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    die("<h1 style='text-align:center; color:red; margin-top:50px;'>Access Denied! 🚫 Only Admin can enter.</h1>");
}

// Category Filter Logic
$cat_filter = isset($_GET['cat']) ? $_GET['cat'] : '';

// Stats fetch karo
$user_count = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$post_count = $conn->query("SELECT COUNT(*) as total FROM posts")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel | MyBlog</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-container { max-width: 1000px; margin: 30px auto; padding: 20px; }
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center; }
        .stat-card h3 { color: #667eea; font-size: 30px; margin: 10px 0; }
        
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); margin-top: 20px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #667eea; color: white; }
        .btn-del { background: #ff4757; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 13px; }
        
        .admin-nav { margin-bottom: 20px; background: #333; padding: 15px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center; }
        .admin-nav a { color: white; text-decoration: none; margin-right: 20px; font-weight: bold; }
        
        .filter-section { background: #fff; padding: 15px; border-radius: 10px; border: 1px solid #ddd; margin-bottom: 20px; display: flex; align-items: center; gap: 15px; }
        .filter-select { padding: 8px; border-radius: 5px; border: 1px solid #ccc; }
    </style>
</head>
<body style="background: #f4f7f6;">

<div class="admin-container">
    <div class="admin-nav">
        <div>
            <a href="dashboard.php">📊 Dashboard</a>
            <a href="../user/create_post.php">➕ Admin Post</a>
            <a href="../index.php">🏠 Website</a>
        </div>
        <a href="../auth/logout.php" style="color: #ff4757;">Logout</a>
    </div>

    <h2>Admin Command Center 🚀</h2>

    <div class="stats-grid">
        <div class="stat-card">
            <p>Total Users</p>
            <h3><?php echo $user_count; ?></h3>
        </div>
        <div class="stat-card">
            <p>Total Posts</p>
            <h3><?php echo $post_count; ?></h3>
        </div>
    </div>

    <div class="filter-section">
        <strong>Filter Posts by Category:</strong>
        <form method="GET" action="dashboard.php">
            <select name="cat" class="filter-select" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <option value="General" <?php if($cat_filter == 'General') echo 'selected'; ?>>General</option>
                <option value="Food" <?php if($cat_filter == 'Food') echo 'selected'; ?>>Food 🍕</option>
                <option value="Travel" <?php if($cat_filter == 'Travel') echo 'selected'; ?>>Travel ✈️</option>
                <option value="Tech" <?php if($cat_filter == 'Tech') echo 'selected'; ?>>Tech 💻</option>
                <option value="Fashion" <?php if($cat_filter == 'Fashion') echo 'selected'; ?>>Fashion 👗</option>
                <option value="Fitness" <?php if($cat_filter == 'Fitness') echo 'selected'; ?>>Fitness 💪</option>
            </select>
        </form>
    </div>

    <h3>Manage Posts (<?php echo $cat_filter ? $cat_filter : 'All'; ?>) 📝</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Title</th>
            <th>Category</th>
            <th>Posted By</th>
            <th>Action</th>
        </tr>
        <?php
        // SQL query with Filter
        $sql_posts = "SELECT posts.id, posts.title, posts.image, posts.category, users.username 
                      FROM posts JOIN users ON posts.user_id = users.id";
        
        if($cat_filter != '') {
            $sql_posts .= " WHERE posts.category = '$cat_filter'";
        }
        
        $sql_posts .= " ORDER BY posts.id DESC";
        $posts = $conn->query($sql_posts);

        while($p = $posts->fetch_assoc()){
        ?>
            <tr>
                <td><?php echo $p['id']; ?></td>
                <td><img src="../uploads/<?php echo $p['image']; ?>" width="50" style="border-radius:5px;"></td>
                <td><?php echo $p['title']; ?></td>
                <td><span style="background:#eee; padding:2px 8px; border-radius:10px; font-size:12px;"><?php echo $p['category']; ?></span></td>
                <td><?php echo $p['username']; ?></td>
                <td><a href="manage_action.php?del_post=<?php echo $p['id']; ?>" class="btn-del" onclick="return confirm('Delete this post?')">Remove</a></td>
            </tr>
        <?php } ?>
    </table>

    <br><hr><br>

    <h3>Manage Users 👤</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php
        $users = $conn->query("SELECT * FROM users WHERE role != 'admin'");
        while($u = $users->fetch_assoc()){
            echo "<tr>
                <td>{$u['id']}</td>
                <td>{$u['username']}</td>
                <td>{$u['email']}</td>
                <td><a href='manage_action.php?del_user={$u['id']}' class='btn-del' onclick='return confirm(\"Delete user?\")'>Delete User</a></td>
            </tr>";
        }
        ?>
    </table>
</div>

</body>
</html>