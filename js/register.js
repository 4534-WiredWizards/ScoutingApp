$(document).ready(function() {
   $('#registerForm').on('submit', function() {
      // http://api.jquery.com/jquery.ajax/
      // api/register
      console.log($(this).serialize());
      return false;
   });
});
