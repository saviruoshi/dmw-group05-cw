<?php
include 'db.php';

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$password = $_POST['password'];

$sql = "INSERT INTO user (first_name, last_name, email, password) 
        VALUES ('$first_name', '$last_name', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    header("Location: Login.html");
} else {
    echo "Error: " . $conn->error;
}
?>
