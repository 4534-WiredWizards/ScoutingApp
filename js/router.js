// Account for director.js bug (https://github.com/flatiron/director/issues/324) where changing the route 500 ms after page load will throw error
function setRouteSafe(router, route) {
   if (window.onpopstate) {
      router.setRoute(base+route);
   } else {
      setTimeout(function() {
         router.setRoute(base+route);
      }, 550);
   }
}

// We want router and base to be in global scope
var router, base, $overlay;
$(document).ready(function() {
   // get <base href="..."> element value
   base = $("base").attr("href");

   var baseRoute = {};
   baseRoute[base] = routes.getObject();
   routes.base = base;

   // Initialize director.js router
   router = Router(baseRoute);
   router.configure({
      html5history: true,
      before: [routes.destroyExisting.bind(routes), function() {
         messages.reset().render();
         if ($('.collapse.in').length > 0) {
            // Close the navbar on mobile when clicking nav link
            $('.navbar-toggle').click();
         }
         $(window).scrollTop(0);
      }],
      notfound: (function() {
         setRouteSafe(this, "not-found");
      }).bind(router),
   });

   router.init();
   if (!router.getRoute()[routes.base.split("/").length-2]) {
      // Set default route or signin if url route isn't set.
      if (token.get()) {
         setRouteSafe(router, routes.defaultUrl);
      } else {
         setRouteSafe(router, "signin");
      }
   }
});
