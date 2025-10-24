<?php
include 'includes/auth.php';
include 'includes/db.php';
include 'includes/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
  crossorigin="anonymous" referrerpolicy="no-referrer" />


<!-- Notification container -->
<div id="notification" class="fixed top-5 right-5 hidden p-4 rounded-lg shadow-lg text-white z-50"></div>

<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-100 flex items-center justify-center py-10 px-4">

  <div
    class="bg-white shadow-2xl rounded-3xl p-8 sm:p-10 w-full max-w-lg transform transition-all duration-500 hover:scale-105">

    <div class="text-center mb-8">
      <i class="fas fa-file-upload text-5xl text-indigo-500 mb-4 animate-bounce-in"></i>
      <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Upload Your Notes</h1>
      <p class="text-gray-500 mt-2">Share your knowledge with the community.</p>
    </div>

    <form method="POST" action="php/upload_note.php" enctype="multipart/form-data" class="space-y-6">

      <div class="relative">
        <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
          <i class="fas fa-heading"></i>
        </div>
        <input type="text" id="note-title" name="title" placeholder="Note Title" required
          class="w-full pl-12 pr-4 py-3 h-14 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-200 focus:border-indigo-400 transition-all duration-300" />
      </div>

      <div class="relative">
        <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
          <i class="fas fa-book-open"></i>
        </div>
        <input type="text" name="subject" placeholder="Subject (e.g., Computer Science)" required
          class="w-full pl-12 pr-4 py-3 h-14 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-200 focus:border-indigo-400 transition-all duration-300" />
      </div>

      <div>
        <textarea name="description" placeholder="Description (optional)" rows="4"
          class="w-full px-4 py-3 min-h-[120px] resize-none border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-200 focus:border-indigo-400 transition-all duration-300"></textarea>
      </div>

      <div>
        <label for="note_file" class="block mb-2 text-sm font-semibold text-gray-700">Upload File</label>
        <div class="relative w-full">
          <input type="file" id="note_file" name="note_file" required class="hidden" onchange="updateFileName(this);" />
          <label for="note_file"
            class="flex items-center justify-between cursor-pointer px-4 py-3 border-2 border-dashed border-indigo-300 text-indigo-500 rounded-xl hover:bg-indigo-50 hover:border-indigo-400 transition-colors duration-300">
            <span id="file-name" class="truncate text-gray-500 font-medium">No file chosen</span>
            <span
              class="bg-indigo-600 text-white text-xs font-bold py-2 px-4 rounded-full shadow-md hover:bg-indigo-700 transition">Browse</span>
          </label>
        </div>
      </div>

      <div>
        <button type="submit"
          class="w-full bg-indigo-600 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-indigo-700 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
          <i class="fas fa-cloud-upload-alt mr-2"></i>
          Upload Note
        </button>
      </div>
    </form>

    <script>
      function updateFileName(input) {
        const fileNameSpan = document.getElementById('file-name');
        const noteTitleInput = document.getElementById('note-title');

        if (input.files.length > 0) {
          // Update the custom file label with the file name
          fileNameSpan.textContent = input.files[0].name;

          // Get the file name without the extension and set it as the title
          const fileName = input.files[0].name;
          const titleWithoutExtension = fileName.split('.').slice(0, -1).join('.');
          noteTitleInput.value = titleWithoutExtension;
        } else {
          fileNameSpan.textContent = 'No file chosen';
          noteTitleInput.value = '';
        }
      }
    </script>
  </div>
</div>


<!-- jQuery CDN (if not already included) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

<script>
  $(document).ready(function() {
    // Handle form submission
    $('form').on('submit', function(e) {
      e.preventDefault(); // prevent default page reload

      var formData = new FormData(this); // include files and form data

      $.ajax({
        url: $(this).attr('action'), // php/upload_note.php
        type: 'POST',
        data: formData,
        contentType: false, // required for FormData
        processData: false, // required for FormData
        beforeSend: function() {
          // Disable submit button & show uploading
          $('button[type="submit"]').prop('disabled', true).html(
            '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...');
        },
        success: function(response) {
          // Show dynamic notification
          showNotification(response.status, response.message);

          if (response.status === 'success') {
            // Reset form & file label
            $('form')[0].reset();
            $('#file-name').text('No file chosen');

            // Optional: Redirect after 2 seconds
            setTimeout(function() {
              window.location.href = 'dashboard.php';
            }, 2000);
          }

          // Re-enable button
          $('button[type="submit"]').prop('disabled', false).html(
            '<i class="fas fa-cloud-upload-alt mr-2"></i>Upload Note');
        },
        error: function(xhr, status, error) {
          showNotification('error', 'Something went wrong: ' + error);
          $('button[type="submit"]').prop('disabled', false).html(
            '<i class="fas fa-cloud-upload-alt mr-2"></i>Upload Note');
        }
      });
    });

    // Notification function
    function showNotification(type, message) {
      var notif = $('#notification');
      notif.removeClass('bg-green-500 bg-red-500')
        .addClass(type === 'success' ? 'bg-green-500' : 'bg-red-500')
        .text(message)
        .fadeIn();

      // Auto-hide after 3 seconds
      setTimeout(function() {
        notif.fadeOut();
      }, 3000);
    }

    // Update file name and autofill title
    $('#note_file').on('change', function() {
      var fileName = this.files[0] ? this.files[0].name : 'No file chosen';
      $('#file-name').text(fileName);
      if (this.files[0]) {
        var titleWithoutExt = fileName.split('.').slice(0, -1).join('.');
        $('#note-title').val(titleWithoutExt);
      }
    });
  });
</script>

<?php include 'includes/footer.php'; ?>