<?php
session_start();
include_once '../includes/db.php'; // Database connection
include_once '../includes/header.php'; // Header file

if (isset($_POST['login'])) {
 $username = $_POST['username'];
 $password = md5($_POST['password']);

 $sql = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
 $result = $conn->query($sql);

 if ($result->num_rows === 1) {
  $_SESSION['admin'] = $username;
  header('Location: dashboard.php');
 } else {
  $error = "Invalid credentials.";
 }
}
?>

<!-- Tailwind Styled Login Form -->
<div class="min-h-screen bg-gray-100 flex items-center justify-center px-4">
 <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">
  <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Admin Login</h2>

  <?php if (isset($error)) echo "<p class='text-red-500 text-sm text-center mb-4'>$error</p>"; ?>

  <form method="post" class="space-y-4">
   <div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
    <input type="text" name="username" required
     class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
   </div>

   <div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
    <input type="password" name="password" required
     class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
   </div>

   <button name="login"
    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition duration-200">
    Login
   </button>
  </form>
 </div>
</div>

<?php
include_once '../includes/footer.php'; // Footer file
if (isset($conn)) $conn->close(); // Close the database connection
?>