var token = new TokenManager("ww-scouting");
var routes = new RoutesManager([], "", "home", token);

routes.register("/home", {
   template: "templates/index.html",
   init: function() {
      console.log("home");
   },
});
routes.register("/register", {
   template: "templates/register.html",
   init: function() {
      console.log("register");
   },
});
routes.register("/signin", {
   template: "templates/signin.html",
   init: function() {
      console.log("signin");
   },
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

function setRouteSafe(router, route) {
   if (window.onpopstate) {
      router.setRoute(base+route);
   } else {
      setTimeout(function() {
         router.setRoute(base+route);
      }, 500);
   }
}

var router, base;
$(document).ready(function() {
   base = $("base").attr("href");
   
   var baseRoute = {};
   baseRoute[base] = routes.getObject();
   routes.base = base;

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
      setRouteSafe(router, routes.defaultUrl);
   }

   $("body").on("click", "[href]:not([href*=\"http\"]):not([href*=\"#\"])", function() {
      var href = $(this).attr("href");
      if (href.charAt(0) == "/") href = href.slice(1);
      setRouteSafe(router, href);
      return false;
   });
});
