<?php
include 'db.php';
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $_SESSION['email'] = $email;
    header("Location: Main.html");
} else {
    echo "Invalid credentials";
}
?>
