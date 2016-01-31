// We want router to be in global scope
var router, $overlay;
$(document).ready(function() {
   // Initialize director.js router
   router = Router(routes.getObject());
   router.configure({
      html5history: false,
      before: [routes.destroyExisting.bind(routes), function() {
         messages.reset().render();
         if ($('.collapse.in').length > 0) {
            // Close the navbar on mobile when clicking nav link
            $('.navbar-toggle').click();
         }
         $(window).scrollTop(0);
      }],
      notfound: (function() {
         this.setRoute("not-found");
      }).bind(router),
   });

   router.init();
   if (!router.getRoute()[0]) {
      // Set default route or signin if url route isn't set.
      if (token.get()) {
         router.setRoute(routes.defaultUrl);
      } else {
         router.setRoute("signin");
      }
   }
});
