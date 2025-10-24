<?php
include '../includes/db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// ✅ Validate file type
$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
if (!in_array($_FILES['profile_photo']['type'], $allowedTypes)) {
 die("Only JPG, JPEG, PNG, or WEBP images are allowed.");
}

// ✅ Sanitize and prepare upload
$photoName = preg_replace("/[^a-zA-Z0-9.\-_]/", "", $_FILES['profile_photo']['name']);
$photoTmp = $_FILES['profile_photo']['tmp_name'];
$uploadDir = '../php/uploads/';
$photoPath = $uploadDir . time() . '_' . basename($photoName);

// Ensure uploads folder exists
if (!file_exists($uploadDir)) {
 mkdir($uploadDir, 0777, true);
}

// Move the uploaded file
if (!move_uploaded_file($photoTmp, $photoPath)) {
 die("Failed to upload profile photo.");
}

$photoDBPath = substr($photoPath, 3); // store path like "uploads/file.jpg"

// ✅ Check for existing email
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
 echo "Email already registered.";
} else {
 $sql = "INSERT INTO users (name, email, password, profile_photo) 
            VALUES ('$name', '$email', '$password', '$photoDBPath')";

 if ($conn->query($sql)) {
  echo "Registered successfully. <a href='../index.php'>Login Now</a>";
 } else {
  echo "Error: " . $conn->error;
 }
}

$conn->close();
