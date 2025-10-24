<?php
include 'includes/auth.php';
include 'includes/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
 header("Location: login.php");
 exit();
}

$user_id = $_SESSION['user_id'];

// Start a transaction to ensure all or none of the deletions happen
$conn->begin_transaction();

try {
 // 1. Delete all likes by the user
 $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = ?");
 $stmt->bind_param("i", $user_id);
 $stmt->execute();
 $stmt->close();

 // 2. Delete all notes uploaded by the user
 // First, get the file paths to delete the physical files
 $stmt = $conn->prepare("SELECT file_path FROM notes WHERE user_id = ?");
 $stmt->bind_param("i", $user_id);
 $stmt->execute();
 $result = $stmt->get_result();
 while ($row = $result->fetch_assoc()) {
  if (file_exists($row['file_path'])) {
   unlink($row['file_path']); // Delete the actual file from the server
  }
 }
 $stmt->close();

 // Now, delete the note records from the database
 $stmt = $conn->prepare("DELETE FROM notes WHERE user_id = ?");
 $stmt->bind_param("i", $user_id);
 $stmt->execute();
 $stmt->close();

 // 3. Delete the user's profile photo
 $stmt = $conn->prepare("SELECT profile_photo FROM users WHERE id = ?");
 $stmt->bind_param("i", $user_id);
 $stmt->execute();
 $result = $stmt->get_result();
 $user_data = $result->fetch_assoc();
 if ($user_data && file_exists($user_data['profile_photo'])) {
  unlink($user_data['profile_photo']);
 }
 $stmt->close();

 // 4. Finally, delete the user's account from the users table
 $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
 $stmt->bind_param("i", $user_id);
 $stmt->execute();
 $stmt->close();

 // If all queries were successful, commit the transaction
 $conn->commit();

 // Log the user out and redirect to the login page
 session_destroy();
 header("Location: login.php?message=Account successfully deleted.");
 exit();
} catch (Exception $e) {
 // If an error occurred, roll back the transaction
 $conn->rollback();
 session_destroy(); // Destroy session to prevent a broken account state
 header("Location: login.php?error=An error occurred while deleting the account.");
 exit();
}

$conn->close();
