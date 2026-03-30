<?php
session_start();
include("../config/db.php");

$id = $_GET['id'];

if(isset($_POST['update'])){
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("UPDATE posts SET title=?, content=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $content, $id);
    $stmt->execute();

    header("Location: dashboard.php");
}

$post = $conn->query("SELECT * FROM posts WHERE id=$id")->fetch_assoc();
?>

<form method="POST">
    <input type="text" name="title" value="<?php echo $post['title']; ?>">
    <textarea name="content"><?php echo $post['content']; ?></textarea>
    <button name="update">Update</button>
</form>