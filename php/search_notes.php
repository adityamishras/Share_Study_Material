<?php
include '../includes/db.php';
include '../includes/auth.php';

$search = $_GET['search'] ?? '';
$user_id = $_SESSION['user_id'];

if (!empty($search)) {
  $search = $conn->real_escape_string($search);
  $sql = "SELECT notes.*, users.name AS uploader_name FROM notes 
            JOIN users ON notes.user_id = users.id 
            WHERE notes.title LIKE '%$search%' OR notes.subject LIKE '%$search%' OR notes.description LIKE '%$search%'
            ORDER BY notes.created_at DESC";
} else {
  $sql = "SELECT notes.*, users.name AS uploader_name FROM notes 
            JOIN users ON notes.user_id = users.id 
            ORDER BY notes.created_at DESC";
}

$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $note_id = $row['id'];
    $like_sql = "SELECT COUNT(*) AS total_likes FROM likes WHERE note_id=$note_id";
    $like_result = $conn->query($like_sql);
    $likes = $like_result->fetch_assoc()['total_likes'];

    $check_sql = "SELECT * FROM likes WHERE user_id=$user_id AND note_id=$note_id";
    $check_res = $conn->query($check_sql);
    $liked = $check_res->num_rows > 0;
?>
    <div
      class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 overflow-hidden transform hover:-translate-y-2 p-6">
      <div class="flex items-center justify-between mb-2">
        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 truncate"><?= htmlspecialchars($row['title']); ?></h3>
        <span class="text-xs text-gray-500 dark:text-gray-400"><?= date('M d, Y', strtotime($row['created_at'])); ?></span>
      </div>
      <p class="text-sm text-indigo-600 dark:text-indigo-400 font-semibold mb-2"><?= htmlspecialchars($row['subject']); ?>
      </p>
      <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-4 line-clamp-3">
        <?= nl2br(htmlspecialchars($row['description'])); ?></p>
      <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
        <span>Uploaded by <span
            class="font-semibold text-gray-700 dark:text-gray-300"><?= htmlspecialchars($row['uploader_name']); ?></span></span>
      </div>
      <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-4">
          <a href="<?= htmlspecialchars($row['file_path']); ?>" target="_blank"
            class="text-indigo-500 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition"><i
              class="fas fa-eye text-2xl"></i></a>
          <a href="<?= htmlspecialchars($row['file_path']); ?>" download
            class="text-green-500 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 transition"><i
              class="fas fa-download text-2xl"></i></a>
        </div>
        <button class="like-btn flex items-center gap-1" data-id="<?= $note_id; ?>">
          <i class="<?= $liked ? 'fa-solid fa-heart text-pink-500' : 'fa-regular fa-heart'; ?> text-2xl"></i>
          <span class="like-count"><?= $likes; ?></span>
        </button>
      </div>
    </div>
<?php
  }
} else {
  echo '<div class="text-center p-16 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700">
            <i class="fas fa-exclamation-circle text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <p class="text-xl font-semibold text-gray-500 dark:text-gray-400">No notes found.</p>
          </div>';
}
?>