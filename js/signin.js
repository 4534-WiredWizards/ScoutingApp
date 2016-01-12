$(document).ready(function() {
   $('#signupForm').on('submit', function() {
      // http://api.jquery.com/jquery.ajax/
      // api/auth
      console.log($(this).serialize())
      return false;
   });
});
