<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){ header("Location: ../auth/login.php"); exit(); }

if(isset($_POST['submit'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category = $_POST['category']; // New Field
    $user_id = $_SESSION['user_id'];

    $image_name = time() . "_" . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $image_name);

    $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content, image, category) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $title, $content, $image_name, $category);
    $stmt->execute();
    header("Location: ../index.php");
}
?>

<?php include("../includes/header.php"); ?>

<div class="login-container">
    <h2>Create Post 📸</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Post Title" required>
        
        <select name="category" style="width:100%; padding:10px; margin:10px 0; border-radius:8px; border:1px solid #ddd;">
            <option value="General">General</option>
            <option value="Food">Food 🍕</option>
            <option value="Travel">Travel ✈️</option>
            <option value="Tech">Tech 💻</option>
            <option value="Fashion">Fashion 👗</option>
            <option value="Fitness">Fitness 💪</option>
        </select>

        <textarea name="content" placeholder="Write something..." style="width:100%; height:80px; padding:10px; border-radius:8px; border:1px solid #ddd;"></textarea>
        <input type="file" name="image" accept="image/*" required>
        <button name="submit">Post</button>
    </form>
</div>