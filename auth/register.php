<?php
session_start();
include("../config/db.php");

if(isset($_POST['register'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username,email,password) VALUES (?,?,?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if($stmt->execute()){
        header("Location: login.php");
        exit();
    } else {
        $error = "Registration Failed!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="login-container">
    <h2>Register</h2>

    <?php if(isset($error)){ ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="email" name="email" placeholder="Enter Email" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button name="register">Register</button>
    </form>

    <p>Already have account? <a href="login.php">Login</a></p>
</div>

</body>
</html>