<?php
include '../includes/db.php';
include '../includes/auth.php';

header('Content-Type: application/json'); // important for AJAX

$user_id = $_SESSION['user_id'];
$title = $_POST['title'];
$subject = $_POST['subject'];
$description = $_POST['description'];
$file = $_FILES['note_file'];

$targetDir = "../uploads/";
$fileName = basename($file["name"]);
$filePath = $targetDir . time() . "_" . $fileName;

$fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
$allowedTypes = ['pdf', 'doc', 'docx'];

if (!in_array($fileType, $allowedTypes)) {
 echo json_encode(['status' => 'error', 'message' => 'Only PDF, DOC, and DOCX files are allowed.']);
 exit;
}

if (move_uploaded_file($file["tmp_name"], $filePath)) {
 $fileDBPath = str_replace("../", "", $filePath); // store path from project root
 $stmt = $conn->prepare("INSERT INTO notes (user_id, title, subject, description, file_path) VALUES (?, ?, ?, ?, ?)");
 $stmt->bind_param("issss", $user_id, $title, $subject, $description, $fileDBPath);

 if ($stmt->execute()) {
  echo json_encode(['status' => 'success', 'message' => 'Note uploaded successfully!']);
 } else {
  echo json_encode(['status' => 'error', 'message' => 'Failed to save note in database.']);
 }
} else {
 echo json_encode(['status' => 'error', 'message' => 'Failed to upload file.']);
}
