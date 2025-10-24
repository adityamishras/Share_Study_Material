$(document).ready(function () {

 // Real-time email format validation
 $('#reg_email').on('input', function () {
  const email = $(this).val();
  const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  if (!isValid) {
   $(this).css('border-color', 'red');
  } else {
   $(this).css('border-color', '#4ade80'); // green border if valid
  }
 });

 // Password strength check (basic length check)
 $('#reg_password').on('input', function () {
  const pass = $(this).val();
  if (pass.length < 6) {
   $(this).css('border-color', 'red');
  } else {
   $(this).css('border-color', '#4ade80');
  }
 });

 // Preview selected profile photo
 $('#profile_photo').on('change', function (event) {
  const file = event.target.files[0];
  if (file && file.type.startsWith('image/')) {
   const reader = new FileReader();
   reader.onload = function (e) {
    if ($('#photo_preview').length === 0) {
     $('<img>', {
      id: 'photo_preview',
      src: e.target.result,
      class: 'mt-3 w-24 h-24 rounded-full object-cover border-2 border-gray-300'
     }).insertAfter('#profile_photo');
    } else {
     $('#photo_preview').attr('src', e.target.result);
    }
   };
   reader.readAsDataURL(file);
  }
 });

 // Prevent form submit if any field is empty
 $('#register_form').on('submit', function (e) {
  let valid = true;
  $(this).find('input[required]').each(function () {
   if ($(this).val() === '') {
    $(this).css('border-color', 'red');
    valid = false;
   }
  });

 });
});