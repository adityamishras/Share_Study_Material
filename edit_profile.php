<?php
include 'includes/auth.php';
include 'includes/db.php';
include 'includes/header.php'; // Assuming this includes your Tailwind CSS and other head content

$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

// Fetch current user data to pre-fill the form
$stmt = $conn->prepare("SELECT name, email, profile_photo FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 $new_name = trim($_POST['name']);
 $new_email = trim($_POST['email']);
 $new_photo_path = $user['profile_photo']; // Default to current photo

 // Validate input
 if (empty($new_name) || empty($new_email)) {
  $error = "Name and Email are required fields.";
 } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
  $error = "Invalid email format.";
 } else {
  // Handle profile photo upload
  if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
   $photo = $_FILES['profile_photo'];
   $file_name = uniqid() . '_' . basename($photo['name']);
   $target_dir = "uploads/profiles/";
   if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
   }
   $target_file = $target_dir . $file_name;
   $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
   $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

   // Check if file is an actual image
   $check = getimagesize($photo['tmp_name']);
   if ($check === false) {
    $error = "File is not an image.";
   } elseif (!in_array($imageFileType, $allowed_types)) {
    $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
   } elseif (move_uploaded_file($photo['tmp_name'], $target_file)) {
    // If a new photo is uploaded and the old one is not the default, delete the old one
    if ($user['profile_photo'] != 'images/default_profile.png') {
     if (file_exists($user['profile_photo'])) {
      unlink($user['profile_photo']);
     }
    }
    $new_photo_path = $target_file;
   } else {
    $error = "Sorry, there was an error uploading your file.";
   }
  }

  // Only proceed if there were no upload errors
  if (empty($error)) {
   // Update user info in the database
   $update_stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, profile_photo = ? WHERE id = ?");
   $update_stmt->bind_param("sssi", $new_name, $new_email, $new_photo_path, $user_id);

   if ($update_stmt->execute()) {
    $message = "Profile updated successfully!";
    // Update session variables
    $_SESSION['user_name'] = $new_name;
    $_SESSION['profile_photo'] = $new_photo_path;

    // Refresh the user data for the form
    $stmt = $conn->prepare("SELECT name, email, profile_photo FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
   } else {
    $error = "Error updating profile: " . $conn->error;
   }
   $update_stmt->close();
  }
 }
}
?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-500 py-12 px-4 sm:px-6 lg:px-8">
 <div class="max-w-xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden p-8 sm:p-12">
  <h2 class="text-3xl font-extrabold text-center text-gray-900 dark:text-gray-100 mb-8">
   Edit Your Profile
  </h2>

  <?php if ($message): ?>
   <div
    class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 px-4 py-3 rounded-md mb-6 transition-all duration-300"
    role="alert">
    <p class="font-bold">Success!</p>
    <p class="text-sm"><?php echo htmlspecialchars($message); ?></p>
   </div>
  <?php endif; ?>

  <?php if ($error): ?>
   <div
    class="bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 px-4 py-3 rounded-md mb-6 transition-all duration-300"
    role="alert">
    <p class="font-bold">Error!</p>
    <p class="text-sm"><?php echo htmlspecialchars($error); ?></p>
   </div>
  <?php endif; ?>

  <form action="edit_profile.php" method="POST" enctype="multipart/form-data" class="space-y-6">
   <div class="flex flex-col items-center">
    <img src="<?php echo htmlspecialchars($user['profile_photo']); ?>"
     class="w-24 h-24 rounded-full object-cover border-4 border-indigo-500 dark:border-indigo-400 shadow-lg mb-4"
     alt="Current Profile Photo">
    <label for="profile_photo" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
     Change Profile Photo
    </label>
    <input type="file" id="profile_photo" name="profile_photo"
     class="block w-full text-sm text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none file:mr-4 file:py-2 file:px-10 file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800 transition-all">
   </div>

   <div>
    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required
     class="mt-1 block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-all">
   </div>

   <div>
    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required
     class="mt-1 block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-all">
   </div>

   <div>
    <button type="submit"
     class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
     Save Changes
    </button>
   </div>
  </form>

 </div>
</div>
<?php
include 'includes/footer.php';
$conn->close();
?>