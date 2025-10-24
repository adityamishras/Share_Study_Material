<?php
include '../includes/db.php';
include '../includes/auth.php';

$user_id = $_SESSION['user_id'];
$note_id = $_GET['note_id'] ?? 0;

if (!$note_id) {
 die("Invalid request.");
}

// Check if the user has already liked the note
$check = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND note_id = ?");
$check->bind_param("ii", $user_id, $note_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
 // Already liked, so unlike (delete the row)
 $delete = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND note_id = ?");
 $delete->bind_param("ii", $user_id, $note_id);
 $delete->execute();

 echo json_encode(["status" => "unliked"]);
} else {
 // Not liked, so like (insert a new row)
 $insert = $conn->prepare("INSERT INTO likes (user_id, note_id) VALUES (?, ?)");
 $insert->bind_param("ii", $user_id, $note_id);
 $insert->execute();

 echo json_encode(["status" => "liked"]);
}
$check->close();
$conn->close();
