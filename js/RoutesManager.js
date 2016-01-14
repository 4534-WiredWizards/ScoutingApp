/**
 * Helper class for registering routes for director
 * @class
 * @example https://github.com/4534-WiredWizards/ScoutingApp2016/blob/297931edda53dfbda3d67c4b8e66cc677ad9eb2b/js/router.js
 */
var RoutesManager = (function() {
   /**
    * Constructor
    * @example https://github.com/4534-WiredWizards/ScoutingApp2016/blob/master/js/router.js#L2
    *
    * @param {array}        routes
    * @param {string}       base
    * @param {string}       defaultUrl
    * @param {TokenManager} tokenManager
    */
   function RoutesManager(routes, base, defaultUrl, tokenManager) {
      this.base = base || "/";
      this.defaultUrl = defaultUrl || "";
      this.routes = routes || [];
      this.tokenManager = tokenManager;
   }

   /**
    * Get a director.js compatible object of routes
    *
    * @return {object} director.js compatible object of routes
    */
   RoutesManager.prototype.getObject = function() {
      var _this = this;
      var res = {};
      this.routes.forEach(function(route) {
         res[route.url] = function() {
            var router = this;
            if (_this.checkToken(route, this.tokenManager)) {
               return setRouteSafe(router, "signin");
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

   /**
    * Register a route
    * @example https://github.com/4534-WiredWizards/ScoutingApp2016/blob/master/js/router.js#L4
    *
    * @param {string} url   The route url
    * @param {object} route Route options
    */
   RoutesManager.prototype.register = function(url, route) {
      route.url = url;
      this.routes.push(route);
   }

   /**
    * Call `destroy` on all initialized routes
    * @example https://github.com/4534-WiredWizards/ScoutingApp2016/blob/297931edda53dfbda3d67c4b8e66cc677ad9eb2b/js/router.js#L80
    *
    * @return {array} Routes that were "destroyed"
    */
   RoutesManager.prototype.destroyExisting = function() {
      return this.routes.filter(function(route) {
         return route.initialized && typeof route.destroy == "function";
      }).forEach(function(route) {
         route.initialized = false;
         route.destroy.call(route);
      });
   }

   /**
    * Determines whether the user has access to a route
    * @example https://github.com/4534-WiredWizards/ScoutingApp2016/blob/297931edda53dfbda3d67c4b8e66cc677ad9eb2b/js/RoutesManager.js#L17
    * @return {boolean} The user doesn't have access
    */
   RoutesManager.prototype.checkToken = function(route, token) {
      return route.requireSignin && !this.tokenManager.get();
   }

   return RoutesManager;
}());
