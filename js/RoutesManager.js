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
    * @return {object} director.js compatible object of routes in this format:
    * {
          "/some-route": function() {
             // This is a callback
          },
          "/another-route": function() {
             // This is another callback
          }
      }
    */
   RoutesManager.prototype.getObject = function() {
      var _this = this;
      var res = {};
      this.routes.forEach(function(route) {
         route.titleElem = route.titleElem || ".main-title";
         route.elem = route.elem || ".main";
         route.dataCallbacks = route.dataCallbacks || {};
         route.authCallback = route.authCallback || function(router, done) {
            return done(1);
         };
         route.contentMethod = route.contentMethod || "html";
         route.data = route.data || {};

         route.updateTitle = (route.updateTitle || function(template, data) {
            var _this = this;
            var contents = (new Ractive({
               template: (template || ""),
               data: (data || {})
            })).toHTML();
            if (template.toLowerCase().search("loading") === -1 && contents.length > 0) {
               $("title").html("ScoutFRC - " + contents);
            } else {
               $("title").html("ScoutFRC");
            }
            $(this.titleElem).html(contents);
            return contents;
         });

         if (!route.dataCallbacks.template && route.template) {
            route.dataCallbacks.template = function(_this, callback) {
               if (_this.templateHTML) {
                  callback(_this.templateHTML)
               } else {
                  $.get(_this.template, function(contents) {
                     _this.templateHTML = contents;
                     callback(contents);
                  });
               }
            }
         }
         route.dataCallback = function(args, done) {
            var methods = [];
            for(var key in this.dataCallbacks) {
               methods.push([key, this.dataCallbacks[key], this]);
            }
            var n = methods.length;
            if (n === 0) {
               return done({});
            }
            var _this = this;
            var data = {};
            methods.forEach(function(method) {
               method[1].apply(method, [_this, function(res) {
                  data[method[0]] = res;
                  n--;
                  if (n === 0) {
                     done(data);
                  }
               }].concat(args));
            });
         }
         res[route.url] = function() {
            var args = Array.prototype.slice.call(arguments);
            var router = this;

            route.updateTitle("Loading...");
            $(route.elem).html("");

            if (_this.checkToken(route, this.tokenManager)) {
               return router.setRoute("signin");
            }

            route.authCallback.apply(route.authCallback, [route, function(auth) {
               if (!auth) {
                  return router.setRoute(_this.defaultUrl);
               }
               route.dataCallback(args, function(data) {
                  data.template = data.template || "";
                  $(route.elem)[route.contentMethod](route.templateHTML);
                  if (typeof route.init == "function") {
                     route.init.apply(route, [data].concat(args));
                     route.initialized = true;
                     if ($(route.titleElem).html() == "Loading...") {
                        route.updateTitle(route.url.split("/").map(function(word) {
                           return word.charAt(0).toUpperCase() + word.slice(1);
                        }).join(" "));
                     }
                  }
                  var params = getParams({
                     modalTitle: "",
                     modalBody: ""
                  });
                  if (params.modalBody.length) {
                     modal.show(decodeURIComponent(params.modalTitle), decodeURIComponent(params.modalBody));
                  }
               });
            }].concat(args));
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
      var routeObj = {};
      for(var key in route) {
         routeObj[key] = route[key];
      }
      routeObj.url = url;
      this.routes.push(routeObj);
   }

   /**
    * Call `destroy` callback on all initialized routes
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
    * @return {boolean} `true` if the user is denied access, `false` if the user has access
    */
   RoutesManager.prototype.checkToken = function(route, token) {
      return route.requireSignin && !this.tokenManager.get();
   }

   return RoutesManager;
}());

function afterN(n, callback) {
   return function() {
      n--;
      if (n === 0) {
         callback();
      }
   }
}
