// TODO: Organize these helpers.

var Routes = (function() {
   function Routes(routes, base, defaultUrl, tokenManager) {
      this.base = base || "/";
      this.defaultUrl = defaultUrl || "";
      this.routes = routes || [];
      this.tokenManager = tokenManager;
   }

   Routes.prototype.getObject = function() {
      var _this = this;
      var res = {};
      this.routes.forEach(function(route) {
         res[route.url] = function() {
            var router = this;
            if (_this.checkToken(route, this.tokenManager)) {
               return setRouteSafe(router, _this.defaultUrl);
            }
            if (route.template || route.templateHTML) {
               function callback() {
                  var method = route.contentMethod || "html";
                  var elem = route.elem || ".main";
                  $(elem)[method](route.templateHTML);
                  if (typeof route.init == "function") {
                     route.init.call(route);
                     route.initialized = true;
                  }
               }
               if (route.templateHTML) {
                  callback();
               } else {
                  $.get(route.template, function(contents) {
                     route.templateHTML = contents;
                  }).then(callback);
               }
            }
         }
      });
      return res;
   }

   Routes.prototype.register = function(url, route) {
      route.url = url;
      this.routes.push(route);
   }

   Routes.prototype.destroyExisting = function() {
      this.routes.filter(function(route) {
         return route.initialized && typeof route.destroy == "function";
      }).forEach(function(route) {
         route.initialized = false;
         route.destroy.call(route);
      });
   }

   Routes.prototype.checkToken = function(route, token) {
      return route.requireSignin && !this.tokenManager.get();
   }
   
   return Routes;
}())

var token = new TokenManager("ww-scouting");
var routes = new Routes([], "", "home", token);
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
