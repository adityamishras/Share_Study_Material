<?php
include 'includes/auth.php';
include 'includes/db.php';
include 'includes/header.php'; // Assuming this includes Boxicons CSS

$user_id = $_SESSION['user_id'];

// Fetch user info from the database using a prepared statement for security
$stmt = $conn->prepare("SELECT name, email, profile_photo, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $user = $result->fetch_assoc();
?>

  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-500 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden p-8 sm:p-12">

      <div class="flex flex-col items-center gap-6 text-center">
        <img src="<?php echo htmlspecialchars($user['profile_photo']); ?>"
          class="w-32 h-32 rounded-full object-cover border-4 border-indigo-500 dark:border-indigo-400 shadow-lg transition-transform duration-300 transform hover:scale-105"
          alt="Profile Photo">

        <div class="flex items-center gap-2 relative group">
          <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">
            <?php echo htmlspecialchars($user['name']); ?>
          </h2>
          <a href="edit_profile.php"
            class="text-gray-400 dark:text-gray-500 hover:text-indigo-500 dark:hover:text-indigo-400 transition-colors duration-300">
            <i class='bx bxs-edit-alt text-2xl'></i>
          </a>
        </div>

        <p class="text-md text-gray-600 dark:text-gray-400 font-medium">
          <?php echo htmlspecialchars($user['email']); ?>
        </p>
        <p class="text-sm text-gray-400">
          Joined: <span class="font-semibold"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
        </p>
      </div>

      <hr class="my-8 border-t border-gray-200 dark:border-gray-700">

      <div class="space-y-6 text-center">
        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Account Management</h3>

        <p class="text-sm text-gray-500 dark:text-gray-400">
          This action is permanent and cannot be undone. All your uploaded notes and account data will be deleted.
        </p>

        <button id="delete-account-btn"
          class="w-full sm:w-auto px-6 py-3 text-lg font-semibold text-white bg-red-600 rounded-full shadow-lg hover:bg-red-700 transition-colors duration-300 focus:outline-none focus:ring-4 focus:ring-red-500 focus:ring-opacity-50">
          Delete My Account
        </button>
      </div>

    </div>
  </div>

  <div id="delete-modal"
    class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-60 transition-opacity duration-300">
    <div
      class="bg-white dark:bg-gray-800 rounded-lg p-8 m-4 max-w-sm w-full shadow-2xl transform scale-95 opacity-0 transition-all duration-300 ease-in-out">
      <div class="text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
          <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c-.538 0-1.076-.234-1.468-.626L3.626 12.468c-.392-.392-.626-.93-.626-1.468V8.5c0-.987.803-1.79 1.79-1.79h12.42c.987 0 1.79.803 1.79 1.79v2.5c0 .538-.234 1.076-.626 1.468L14.468 18.626c-.392.392-.93.626-1.468.626z" />
          </svg>
        </div>
        <h3 class="mt-4 text-xl leading-6 font-medium text-gray-900 dark:text-gray-100">Delete Account?</h3>
        <div class="mt-2">
          <p class="text-sm text-gray-500 dark:text-gray-400">
            Are you sure you want to delete your account? All of your data will be permanently removed. This action cannot be
            undone.
          </p>
        </div>
      </div>
      <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
        <button id="confirm-delete-btn"
          class="w-full flex-1 px-4 py-2 text-base font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 sm:col-start-2 sm:text-sm">
          Confirm
        </button>
        <button id="cancel-delete-btn"
          class="mt-3 w-full flex-1 px-4 py-2 text-base font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-200 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 sm:mt-0 sm:col-start-1 sm:text-sm">
          Cancel
        </button>
      </div>
    </div>
  </div>

  <script>
    const deleteBtn = document.getElementById('delete-account-btn');
    const deleteModal = document.getElementById('delete-modal');
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    const cancelDeleteBtn = document.getElementById('cancel-delete-btn');

    function openModal() {
      deleteModal.classList.remove('hidden');
      setTimeout(() => {
        deleteModal.querySelector('div').classList.remove('scale-95', 'opacity-0');
        deleteModal.querySelector('div').classList.add('scale-100', 'opacity-100');
      }, 10);
    }

    function closeModal() {
      deleteModal.querySelector('div').classList.remove('scale-100', 'opacity-100');
      deleteModal.querySelector('div').classList.add('scale-95', 'opacity-0');
      setTimeout(() => {
        deleteModal.classList.add('hidden');
      }, 300);
    }

    deleteBtn.addEventListener('click', openModal);

    confirmDeleteBtn.addEventListener('click', function() {
      window.location.href = 'delete_account.php';
    });

    cancelDeleteBtn.addEventListener('click', closeModal);
    deleteModal.addEventListener('click', function(event) {
      if (event.target === deleteModal) {
        closeModal();
      }
    });
  </script>

<?php
} else {
  echo "<p class='text-center py-12 text-red-500 text-lg font-semibold'>User not found.</p>";
}

$stmt->close();
$conn->close();

include 'includes/footer.php';
?>