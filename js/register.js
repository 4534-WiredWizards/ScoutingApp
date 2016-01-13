$(document).ready(function() {
   $('#registerForm').on('submit', function() {
      // http://api.jquery.com/jquery.ajax/
      // api/register
	  $.ajax({
		 url: "api/pages/register.php",
		 data: $('#registerForm').serialize(),
		 method: "POST"
      }).done(function(){
         
      });
	  return false;
   });
});
