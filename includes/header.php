<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost/blogging_site/assets/css/style.css">
    
    <style>
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 8%;
            background: white;
            border-bottom: 1px solid #ddd;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: 0.3s;
        }
        .nav-icons { display: flex; align-items: center; gap: 18px; }
        .nav-icons a, .nav-icons button { 
            text-decoration: none; 
            color: #333; 
            font-size: 20px; 
            background: none; 
            border: none; 
            cursor: pointer; 
            transition: 0.3s; 
        }
        .logo { font-size: 24px; font-weight: bold; color: #667eea; text-decoration: none; }
        .admin-badge { 
            background: #ff4757; 
            color: white !important; 
            padding: 3px 10px; 
            border-radius: 20px; 
            font-size: 13px !important; 
            font-weight: bold;
        }
        
        /* Dark Mode Button Animation */
        #dark-mode-toggle:hover { transform: scale(1.2); }
    </style>
</head>
<body>

<div class="navbar">
    <a href="http://localhost/blogging_site/index.php" class="logo">MyBlog</a>
    
    <div class="nav-icons">
        <button id="dark-mode-toggle" title="Toggle Dark/Light Mode">🌙</button>

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <a href="http://localhost/blogging_site/admin/dashboard.php" class="admin-badge">🛡️ Admin</a>
        <?php endif; ?>

        <a href="http://localhost/blogging_site/index.php" title="Home">🏠</a>
        <a href="http://localhost/blogging_site/user/search.php" title="Search">🔍</a>
        <a href="http://localhost/blogging_site/user/create_post.php" title="Add Post">➕</a>
        <a href="http://localhost/blogging_site/user/dashboard.php" title="Profile">👤</a>
        <a href="http://localhost/blogging_site/auth/logout.php" title="Logout" style="color: #ff4757;">🚪</a>
    </div>
</div>

<script>
    const toggleBtn = document.getElementById('dark-mode-toggle');
    const body = document.body;

    // 1. Check if user already has a saved theme
    if (localStorage.getItem('theme') === 'dark') {
        body.classList.add('dark-mode');
        toggleBtn.innerText = '☀️';
    }

    // 2. Toggle functionality
    toggleBtn.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        
        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('theme', 'dark');
            toggleBtn.innerText = '☀️';
            toggleBtn.style.color = "yellow";
        } else {
            localStorage.setItem('theme', 'light');
            toggleBtn.innerText = '🌙';
            toggleBtn.style.color = "#333";
        }
    });
</script>