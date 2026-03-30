<?php
include("../includes/header.php");
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Users</title>
    <style>
        .search-container { max-width: 500px; margin: 30px auto; padding: 0 15px; }
        .search-input { 
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; 
            font-size: 16px; outline: none; transition: 0.3s;
        }
        .search-input:focus { border-color: #667eea; box-shadow: 0 0 5px rgba(102,126,234,0.3); }
        
        #search-results { margin-top: 20px; }
        .user-card { 
            display: flex; align-items: center; background: white; padding: 12px; 
            margin-bottom: 10px; border-radius: 10px; border: 1px solid #eee; 
            text-decoration: none; color: black; transition: 0.2s;
        }
        .user-card:hover { background: #f9f9f9; transform: translateY(-2px); box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .user-card img { width: 45px; height: 45px; border-radius: 50%; margin-right: 15px; object-fit: cover; border: 1px solid #ddd; }
        .username { font-weight: bold; font-size: 15px; }
    </style>
</head>
<body>

<div class="search-container">
    <h2>Explore Users 🔍</h2>
    <input type="text" id="live-search" class="search-input" placeholder="Type a letter to search..." autocomplete="off">

    <div id="search-results">
        <p style="color: #888; text-align: center; margin-top: 20px;">Start typing to find people...</p>
    </div>
</div>

<script>
    const searchInput = document.getElementById('live-search');
    const resultsDiv = document.getElementById('search-results');

    searchInput.addEventListener('input', function() {
        let query = this.value;

        if (query.length > 0) {
            // AJAX Call
            fetch('fetch_users.php?q=' + query)
            .then(response => response.text())
            .then(data => {
                resultsDiv.innerHTML = data;
            });
        } else {
            resultsDiv.innerHTML = '<p style="color: #888; text-align: center; margin-top: 20px;">Start typing to find people...</p>';
        }
    });
</script>

<?php include("../includes/footer.php"); ?>