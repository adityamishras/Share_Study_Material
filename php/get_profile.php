<?php
include '../includes/auth.php';
include '../includes/db.php';

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $conn->prepare("SELECT name, email, profile_photo, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
 $user = $result->fetch_assoc();
?>
 <div class="flex flex-col items-center gap-4">
  <img src="<?php echo htmlspecialchars($user['profile_photo']); ?>"
   class="w-32 h-32 rounded-full object-cover border-2 border-indigo-400" alt="Profile Photo">
  <h2 class="text-2xl font-bold"><?php echo htmlspecialchars($user['name']); ?></h2>
  <p class="text-gray-500 dark:text-gray-400"><?php echo htmlspecialchars($user['email']); ?></p>
  <p class="text-sm text-gray-400">Joined: <?php echo date('M d, Y', strtotime($user['created_at'])); ?></p>
 </div>
<?php
} else {
 echo "<p class='text-center py-6 text-red-500'>User not found.</p>";
}
?>