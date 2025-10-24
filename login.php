<?php
include_once 'includes/header.php';
?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-100 via-white to-blue-100 p-6">
 <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden flex flex-col md:flex-row w-full max-w-4xl">

  <!-- Left side form -->
  <div class="md:w-1/2 p-6">
   <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Login</h2>
   <form action="php/login.php" method="POST" class="space-y-4">
    <div>
     <label for="login_email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
     <input type="email" name="email" id="login_email" required placeholder="you@example.com" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 
              focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 
              dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    </div>

    <div>
     <label for="login_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
     <input type="password" name="password" id="login_password" required placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 
              focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 
              dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    </div>

    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none 
            focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center 
            dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
     Login
    </button>

    <!-- Optional Register Message -->
    <p class="text-sm text-gray-600 dark:text-gray-300 text-center mt-2">
     Don't have an account?
     <a href="index.php" class="text-blue-600 hover:underline dark:text-blue-400">Register here</a>.
    </p>
   </form>
  </div>

  <!-- Right side image -->
  <div class="md:w-1/2 hidden md:block">
   <img src="assets/images/login-illustration.jpg" alt="login-illustration" class="w-full h-full object-cover">
  </div>

 </div>
</div>

<?php
include_once 'includes/footer.php';
?>