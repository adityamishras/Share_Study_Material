<?php
include 'includes/auth.php';
include 'includes/db.php';
include 'includes/header.php';

$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];
?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<div
   class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-100 dark:from-gray-900 dark:to-gray-800 font-sans text-gray-800 dark:text-gray-200 transition-colors duration-500">

   <!-- 🔹 Top Navbar -->
   <div class="sticky top-0 z-40 bg-white dark:bg-gray-800 shadow-xl transition-colors duration-500">
      <div class="max-w-7xl mx-auto p-6">
         <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex-grow flex flex-col gap-4 sm:flex-row sm:items-center">
               <div class="flex items-center gap-4   p-4 rounded-xl">
                  <img src="<?php echo htmlspecialchars($_SESSION['profile_photo']); ?>"
                     class="w-16 h-16 rounded-full object-cover shadow-inner border-2 border-indigo-400 dark:border-indigo-600 transform hover:scale-105 transition-transform duration-300 cursor-pointer"
                     alt="Profile" onclick="openModal('<?php echo htmlspecialchars($_SESSION['profile_photo']); ?>')">
                  <div>
                     <h2 class="text-xl font-extrabold text-gray-900 dark:text-gray-100">
                        Welcome, <span class="text-indigo-600 dark:text-indigo-400"><?php echo htmlspecialchars($user_name); ?>!</span>
                     </h2>
                  </div>
               </div>
            </div>

            <!-- 🔹 Action Buttons -->
            <div class="flex items-center gap-3">

               <a href="upload.php"
                  class="inline-flex items-center justify-center w-12 h-12 text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900 rounded-full shadow hover:bg-indigo-200 dark:hover:bg-indigo-800 transition-all duration-300 transform hover:scale-110"
                  title="Upload New Note">
                  <i class='bx bx-plus text-xl'></i>
               </a>

               <div class="relative inline-block text-left">
                  <button id="menu-button"
                     class="inline-flex items-center justify-center w-12 h-12 text-gray-600 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-full shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300 transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                     aria-expanded="true" aria-haspopup="true">
                     <i class='bx bxs-user text-xl'></i>
                  </button>
                  <div id="dropdown-menu"
                     class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 hidden z-10">
                     <div class="py-1">
                        <a href="profile.php"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                           <i class='bx bxs-user-circle mr-2'></i> Profile
                        </a>
                        <a href="logout.php"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                           <i class='bx bx-log-out-circle mr-2'></i> Logout
                        </a>
                     </div>
                  </div>
               </div>

               <button id="theme-toggle" type="button"
                  class="inline-flex items-center justify-center w-12 h-12 text-gray-600 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-full shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300 transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                  aria-label="Toggle dark mode">
                  <i id="theme-icon" class='bx bx-moon text-lg'></i>
               </button>

               <a href="settings.php"
                  class="inline-flex items-center justify-center w-12 h-12 text-gray-600 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-full shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300 transform hover:scale-110"
                  title="Settings">
                  <i class='bx bx-cog text-xl'></i>
               </a>
            </div>

            <!-- 🔹 Search Bar -->
            <form method="GET" action="dashboard.php" class="w-full mt-4 sm:mt-0">
               <div class="relative w-full">
                  <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                     <i class='bx bx-search text-xl'></i>
                  </span>
                  <input type="text" id="search-notes" name="search" placeholder="Search notes by title, subject, or description..."
                     class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-full text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-500 transition-all bg-white dark:bg-gray-800">
               </div>
            </form>

         </div>
      </div>
   </div>

   <!-- 🔹 Notes Container -->
   <div id="notes-container" class="max-w-7xl mx-auto px-6 py-10 
            grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
   </div>

   <!-- 🔹 Profile Image Modal -->
   <div id="image-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-75">
      <div class="relative max-w-lg p-4">
         <button onclick="closeModal()" class="absolute -top-10 right-0 text-white text-3xl font-bold p-2">&times;</button>
         <img id="modal-image" src="" alt="Enlarged Profile" class="max-w-full max-h-screen-75 rounded-lg shadow-2xl">
      </div>
   </div>

   <!-- 🔹 jQuery -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

   <script>
      // Profile modal
      function openModal(imageSrc) {
         $('#modal-image').attr('src', imageSrc);
         $('#image-modal').removeClass('hidden');
      }

      function closeModal() {
         $('#image-modal').addClass('hidden');
      }

      // Profile dropdown
      const menuButton = document.getElementById('menu-button');
      const dropdownMenu = document.getElementById('dropdown-menu');
      menuButton.addEventListener('click', function() {
         dropdownMenu.classList.toggle('hidden');
      });
      window.addEventListener('click', function(event) {
         if (!menuButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.add('hidden');
         }
      });

      // Dynamic search with AJAX
      let searchTimeout;

      function loadNotes(query = '') {
         $.ajax({
            url: 'php/search_notes.php',
            type: 'GET',
            data: {
               search: query
            },
            beforeSend: function() {
               $('#notes-container').html('<p class="text-center py-10">Loading...</p>');
            },
            success: function(data) {
               $('#notes-container').html(data);
            },
            error: function() {
               $('#notes-container').html('<p class="text-center py-10 text-red-500">An error occurred.</p>');
            }
         });
      }

      $(document).ready(function() {
         loadNotes();
         $('#search-notes').on('input', function() {
            clearTimeout(searchTimeout);
            const query = $(this).val();
            searchTimeout = setTimeout(() => loadNotes(query), 300);
         });
      });


      $(document).on('click', '.like-btn', function() {
         const btn = $(this);
         const noteId = btn.data('id');

         $.ajax({
            url: 'php/like_note.php',
            type: 'GET',
            data: {
               note_id: noteId
            },
            success: function(response) {
               const res = JSON.parse(response);
               let count = parseInt(btn.find('.like-count').text());

               if (res.status === 'liked') {
                  btn.find('.like-count').text(count + 1);
                  btn.find('i').removeClass('fa-regular fa-heart').addClass('fa-solid fa-heart text-pink-500');
               } else {
                  btn.find('.like-count').text(count - 1);
                  btn.find('i').removeClass('fa-solid fa-heart text-pink-500').addClass('fa-regular fa-heart');
               }
            }
         });
      });
   </script>


   <?php include 'includes/footer.php'; ?>