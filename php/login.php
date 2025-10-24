<?php
session_start();
include '../includes/db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
 $row = $result->fetch_assoc();
 if (password_verify($password, $row['password'])) {
  $_SESSION['user_id'] = $row['id'];
  $_SESSION['user_name'] = $row['name'];
  $_SESSION['profile_photo'] = $row['profile_photo'];
  header('Location: ../dashboard.php');
  exit();
 } else {
  echo "Invalid password.";
 }
} else {
 echo "No account found with this email.";
}
$conn->close();
