// Initialize token manager
var token = new TokenManager("ww-scouting");
// Initialize app url route manager
var routes = new RoutesManager([], "", "home", token);

// Register app routes
routes.register("/home", {
   template: "templates/index.html",
   init: function() {
      console.log("home");
   },
   requireSignin: true
});
routes.register("/register", {
   template: "templates/register.html",
   init: function() {
      console.log("register");
   },
});
routes.register("/signin", {
   template: "templates/signin.html",
});
routes.register("/team/new", {
   template: "templates/team/new.html",
   init: function() {
      console.log("team");
   },
   requireSignin: true
});
routes.register("/team/:teamNum", {
   template: "templates/team/display.html",
   init: function() {
      console.log("team display");
   },
   requireSignin: true
});
routes.register("/team/:teamNum/edit", {
   template: "templates/team/edit.html",
   init: function() {
      console.log("team edit");
   },
   requireSignin: true
});
routes.register("/teams", {
   template: "templates/team/list.html",
   init: function() {
      console.log("team list");
   },
   requireSignin: true
});
routes.register("/not-found", {
   template: "templates/not-found.html",
   init: function() {
      this.interval = window.setInterval(notFoundChange,1000 / 10);
   },
   destroy: function() {
      clearInterval(this.interval);
   },
   requireSignin: false
});

// Account for director.js bug (https://github.com/flatiron/director/issues/324) where changing the route 500 ms after page load will throw error
function setRouteSafe(router, route) {
   if (window.onpopstate) {
      router.setRoute(base+route);
   } else {
      setTimeout(function() {
         router.setRoute(base+route);
      }, 500);
   }
}

// We want router and base to be in global scope
var router, base;
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
      before: routes.destroyExisting.bind(routes),
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

   $("body").on("click", "[href]:not([href*=\"http\"]):not([href*=\"#\"])", function() {
      // Override links and set route without leaving page
      var href = $(this).attr("href");
      if (href.charAt(0) == "/") href = href.slice(1);
      setRouteSafe(router, href);
      return false;
   });
});
