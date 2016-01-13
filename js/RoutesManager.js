// TODO: Organize these helpers.

var RoutesManager = (function() {
   function RoutesManager(routes, base, defaultUrl, tokenManager) {
      this.base = base || "/";
      this.defaultUrl = defaultUrl || "";
      this.routes = routes || [];
      this.tokenManager = tokenManager;
   }

   RoutesManager.prototype.getObject = function() {
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

   RoutesManager.prototype.register = function(url, route) {
      route.url = url;
      this.routes.push(route);
   }

   RoutesManager.prototype.destroyExisting = function() {
      this.routes.filter(function(route) {
         return route.initialized && typeof route.destroy == "function";
      }).forEach(function(route) {
         route.initialized = false;
         route.destroy.call(route);
      });
   }

   RoutesManager.prototype.checkToken = function(route, token) {
      return route.requireSignin && !this.tokenManager.get();
   }
   
   return RoutesManager;
}());
