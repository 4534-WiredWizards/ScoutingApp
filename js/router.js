// TODO: Organize these helpers.

var Routes = (function() {
   function Routes(routes, base, defaultUrl) {
      this.base = base || "/";
      this.defaultUrl = defaultUrl || "";
      this.routes = routes || [];
   }

   Routes.prototype.getObject = function() {
      var _this = this;
      var res = {};
      this.routes.forEach(function(route) {
         res[route.url] = function() {
            console.log('i am here');
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

   Routes.prototype.checkToken = function(router, token) {
      if (this.routes.filter(function(route) {
         return route.initialized && route.requireLogin;
      }).length) {
         router.setRoute(base+this.defaultUrl);
      }
   }
   
   return Routes;
}())

var routes = new Routes([], "", "home");
routes.register("/home", {
   template: "templates/index.html",
   init: function() {
      console.log("home");
   }
});
routes.register("/register", {
   template: "templates/register.html",
   init: function() {
      console.log("register");
   }
});
routes.register("/signin", {
   template: "templates/signin.html",
   init: function() {
      console.log("signin");
   }
});
routes.register("/team/new", {
   template: "templates/team/new.html",
   init: function() {
      console.log("team");
   }
});
routes.register("/team/:teamNum", {
   template: "templates/team/display.html",
   init: function() {
      console.log("team display");
   }
});
routes.register("/team/:teamNum/edit", {
   template: "templates/team/edit.html",
   init: function() {
      console.log("team edit");
   }
});
routes.register("/team/", {
   template: "templates/team/list.html",
   init: function() {
      console.log("team list");
   }
});
routes.register("/not-found", {
   template: "templates/not-found.html",
   init: function() {
      this.interval = window.setInterval(notFoundChange,1000 / 10);
   },
   destroy: function() {
      clearInterval(this.interval);
   }
});

var router;
$(document).ready(function() {
   var base = $("base").attr("href");
   
   var baseRoute = {};
   baseRoute[base] = routes.getObject();
   routes.base = base;

   router = Router(baseRoute);
   router.configure({
      html5history: true,
      before: routes.destroyExisting.bind(routes),
      notfound: (function() {
         var _this = this;
         if (!window.onpopstate) {
            setTimeout(function() {
               _this.setRoute(base+"not-found");
            }, 500);
         }
      }).bind(router)
   });

   router.init();
   if (!router.getRoute()[routes.base.split("/").length-2]) {
      if (!window.onpopstate) {
         setTimeout(function() {
            router.setRoute(routes.base+routes.defaultUrl);
         }, 500);
      }
   }

   $("body").on("click", "[href]:not([href*=\"http\"]):not([href*=\"#\"])", function() {
      var href = $(this).attr("href");
      if (href.charAt(0) == "/") href = href.slice(1);
      router.setRoute(href);
      return false;
   });
});

var TokenHandler = (function() {
   function TokenHandler(ns) {
      this.ns = ns || "";
   }

   TokenHandler.prototype.get = function() {
      return localStorage.getItem(this.ns+".token") || "";
   }

   TokenHandler.prototype.set = function(token) {
      return localStorage.setItem(this.ns+".token", token || "");
   }

   TokenHandler.prototype.auth = function(data) {
      var _this = this;

      return $.ajax({
         url: "api/auth",
         data: data,
         method: "POST"
      }).then(function(res) {
         if (res.success && res.token) {
            _this.set(res.token);
         }
      });
   }

   return TokenHandler;
})();

var token = new TokenHandler();
