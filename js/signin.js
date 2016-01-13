$('#signinForm').on('submit', function() {
   token.auth($(this).serialize()).then(function(res) {
      if (res.success && res.token && token.get()) {
         setRouteSafe(router, "home");
      } else {
         
      }
   });
   return false;
});
