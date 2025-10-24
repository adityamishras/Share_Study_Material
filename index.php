<?php
include_once 'includes/header.php';
?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-purple-100 p-6">
 <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden flex flex-col md:flex-row w-full max-w-4xl">

  <!-- Left side image -->
  <div class="md:w-1/2 hidden md:block">
   <img src="assets/images/register.jpg" alt="Register Illustration" class="w-full h-full object-cover">
  </div>

  <!-- Right side form -->
  <div class="md:w-1/2 p-6">
   <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Register</h2>
   <form action="php/register.php" method="post" enctype="multipart/form-data" class="space-y-4" id="register_form">
    <div>
     <label for="reg_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
     <input type="text" name="name" id="reg_name" required placeholder="Your name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 
              focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 
              dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    </div>

    <div>
     <label for="reg_email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
     <input type="email" name="email" id="reg_email" required placeholder="you@example.com" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 
              focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 
              dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    </div>

    <div>
     <label for="reg_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
     <input type="password" name="password" id="reg_password" required placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 
              focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 
              dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    </div>

    <div>
     <label for="profile_photo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Profile
      Photo</label>
     <input type="file" name="profile_photo" id="profile_photo" accept="image/*" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer 
              bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 
              dark:placeholder-gray-400">
    </div>

    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none 
            focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center 
            dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
     Register
    </button>

    <!-- Message below the button -->
    <p class="text-sm text-gray-600 dark:text-gray-300 text-center mt-2">
     Already registered?
     <a href="login.php" class="text-blue-600 hover:underline dark:text-blue-400">Login here</a>.
    </p>
   </form>
  </div>

 </div>
</div>

<?php
include_once 'includes/footer.php';
?>