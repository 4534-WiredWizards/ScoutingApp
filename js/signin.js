$('#signinForm').on('submit', function() {
   token.auth($(this).serialize()).then(function(res) {
      if (res.success && res.token && token.get()) {
         router.setRoute(routes.defaultUrl);
      } else {
         // TODO: Display error messages http://getbootstrap.com/components/#alerts
         console.log('errors:', res.error);
      }
   });
   return false;
});
