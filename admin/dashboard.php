<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header('Location: login.php');
  exit();
}
include_once '../includes/header.php';
include_once '../includes/slidebar.php';
include_once '../includes/db.php';
?>
<?php
$sql  = "SELECT COUNT(*) as total FROM users";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$a = $row['total'];
$sql = "SELECT COUNT(*) as total FROM notes";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$b = $row['total'];
$sql = "SELECT COUNT(*) as total FROM likes";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$c = $row['total'];
?>
<div class="container mx-auto mt-10">
  <h1 class="text-3xl font-bold mb-5">Dashboard</h1>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="bg-white shadow-lg rounded-lg p-5">
      <h2 class="text-xl font-semibold">Total Users</h2>
      <p class="text-gray-700"><?php echo $a; ?></p>
    </div>
    <div class="bg-white shadow-lg rounded-lg p-5">
      <h2 class="text-xl font-semibold">Total Notes</h2>
      <p class="text-gray-700"><?php echo $b; ?></p>
    </div>
    <div class="bg-white shadow-lg rounded-lg p-5">
      <h2 class="text-xl font-semibold">Total Likes</h2>
      <p class="text-gray-700"><?php echo $c; ?></p>
    </div>
  </div>

  <?php
  include_once '../includes/footer.php';
  ?>