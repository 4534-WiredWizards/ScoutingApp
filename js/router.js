// Initialize token manager
var token = new TokenManager("ww-scouting", setLoggedin, setNotLoggedin);
var messages = new MessageManager(".alerts", []);

function setLoggedin() {
   if (document.readyState === "complete") {
      $("body").addClass("loggedin");
   } else {
      $(document).ready(function() {
         $("body").addClass("loggedin");
      });
   }
}
function setNotLoggedin() {
   if (document.readyState === "complete") {
      $("body").removeClass("loggedin");
   } else {
      $(document).ready(function() {
         $("body").removeClass("loggedin");
      });
   }
}

// Initialize app url route manager
var routes = new RoutesManager([], "", "team", token);

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
   formSuccess: function(res) {
      setRouteSafe(router, "user/"+res.data.id);
   },
   requireSignin: true
});
routes.register("/signin", {
   template: "templates/signin.html",
   init: function() {
      this.updateTitle("Sign In");
   }
});
routes.register("/team/new", {
   template: "templates/team/form.html",
   init: function(data) {
      ractive = new Ractive({
         el: ".main",
         template: data.template,
         data: {
            action: "team/new"
         }
      })
      this.updateTitle("Add a Team");
   },
   formSuccess: function(res) {
      setRouteSafe(router, "team/"+res.data.team_number);
   },
   requireSignin: true
});
routes.register("/team/:teamNum", {
   template: "templates/team/display.html",
   dataCallbacks: {
      team: function(_this, callback, teamNum) {
         API.get("team/"+teamNum, {}, function(res) {
            res.data = res.data || {};
            res.data.summary = res.data.summary || "";
            res.data.strengths = res.data.strengths || "";
            res.data.weaknesses = res.data.weaknesses || "";
            callback(res.data);
         });
      }
   },
   init: function(data, teamNum) {
      ractive = new Ractive({
         el: ".main",
         template: data.template,
         data: {
            splitLine: function(val) {
               return (val || "").split(/\s*\n\s*/g).filter(Boolean);
            },
            score: 0
         }
      })
      ractive.set(data.team);
      setTimeout(function() {
         ractive.set("score", 50)
      }, 100);
      this.updateTitle("{{team_type}} Team #{{team_number}} - {{team_name}}", data.team);
   },
   requireSignin: true
});
routes.register("/user/:userID", {
   template: "templates/user/display.html",
   dataCallbacks: {
      user: function(_this, callback, userID) {
         API.get("user/"+userID, {}, function(res) {
            res.data = res.data || {};
            callback(res.data);
         });
      }
   },
   init: function(data, userID) {
      ractive = new Ractive({
         el: ".main",
         template: data.template,
         data: {
            splitLine: function(val) {
               return (val || "").split(/\s*\n\s*/g).filter(Boolean);
            },
            score: 0
         }
      })
      ractive.set(data.user);
      this.updateTitle("{{firstname}} {{lastname}} ({{username}})", data.user);
   },
   requireSignin: true
});
routes.register("/user/:userID/edit", {
   template: "templates/user/edit.html",
   dataCallbacks: {
      user: function(_this, callback, userID) {
         API.get("user/"+userID, {}, function(res) {
            res.data.summary = res.data.summary || "";
            res.data.strengths = res.data.strengths || "";
            res.data.weaknesses = res.data.weaknesses || "";
            callback(res.data);
         });
      }
   },
   init: function(data, userID) {
      ractive = new Ractive({
         el: ".main",
         template: data.template,
         data: {
            action: "user/"+userID+"/edit"
         }
      })
      ractive.set(data.user);
      this.updateTitle("{{firstname}} {{lastname}} ({{username}})", data.user);
      $("textarea").each(function() {
         $(this).height(1);
         $(this).height(this.scrollHeight);
      })
   },
   requireSignin: true
});
routes.register("/team/:teamNum/edit", {
   template: "templates/team/form.html",
   dataCallbacks: {
      team: function(_this, callback, teamNum) {
         API.get("team/"+teamNum, {}, function(res) {
            res.data.summary = res.data.summary || "";
            res.data.strengths = res.data.strengths || "";
            res.data.weaknesses = res.data.weaknesses || "";
            callback(res.data);
         });
      }
   },
   init: function(data, teamNum) {
      ractive = new Ractive({
         el: ".main",
         template: data.template,
         data: {
            action: "team/"+teamNum+"/edit"
         }
      })
      ractive.set(data.team);
      this.updateTitle("{{team_type}} Team #{{team_number}} - {{team_name}} - Edit", data.team);
   },
   requireSignin: true
});
routes.register("/team", {
   template: "templates/team/list.html",
   dataCallbacks: {
      teams: function(_this, callback) {
         API.get("team", {}, function(res) {
            callback(res.data);
         });
      }
   },
   init: function(data) {
      var ractive = new Ractive({
         el: ".main",
         template: data.template,
         data: {
            teams: data.teams
         }
      });
      this.updateTitle("Teams");
   },
   requireSignin: true
});
routes.register("/user", {
   template: "templates/user/list.html",
   dataCallbacks: {
      users: function(_this, callback) {
         API.get("user", {}, function(res) {
            callback(res.data);
         });
      }
   },
   init: function(data) {
      var ractive = new Ractive({
         el: ".main",
         template: data.template,
         data: {
            users: data.users
         }
      });
      this.updateTitle("Users");
   },
   requireSignin: true
});
routes.register("/team/search/:query", {
   template: "templates/team/list.html",
   dataCallbacks: {
      teams: function(_this, callback, query) {
         API.get("team", {query: query}, function(res) {
            callback(res.data);
         });
      }
   },
   init: function(data, query) {
      var ractive = new Ractive({
         el: ".main",
         template: data.template,
         data: {
            teams: data.teams,
            query: query
         }
      });
      this.updateTitle("Teams");
   },
   requireSignin: true
});
routes.register("/not-found", {
   template: "templates/not-found.html",
   init: function() {
      this.interval = window.setInterval(notFoundChange, 1000 / 10);
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

   $("body").on("click", "[href]:not([href*=\"http\"]):not([href*=\"#\"])", function() {
      // Override links and set route without leaving page
      var href = $(this).attr("href");
      if (href.charAt(0) == "/") href = href.slice(1);
      setRouteSafe(router, href);
      return false;
   });

   function parseDataArray(arr) {
      var result = {};
      arr.forEach(function(obj) {
         result[obj.name] = decodeURIComponent(obj.value);
      });
      return result;
   }

   $("body").on("submit", "form[method=async][action]", function() {
      var route = routes.routes.filter(function(route) {
         return route.initialized
      })[0] || {};

      route.formSubmit = (route.formSubmit || Function()).bind(route);
      route.formError = (route.formError || Function()).bind(route);
      route.formSuccess = (route.formSuccess || Function()).bind(route);

      var $form = $(this).is("form") ? $(this) : $(this).closest("form");
      var data = parseDataArray($form.serializeArray());

      route.formSubmit($form, data);

      messages.reset();
      messages.concat([{
         text: "Loading...",
         type: "info"
      }]);
      messages.render();
      $(window).scrollTop(0);
      $form.find(".has-error").removeClass(".has-error");

      function getErrorMessageObj(message) {
         if (typeof message == "string") {
            message = {
               msg: message
            };
         }
         if (message.field) {
            $form.find('[name="'+message.field+'"]').closest('.form-group').addClass('has-error');
         }
         return {
            text: message.msg,
            type: "danger"
         };
      }

      API.post($form.attr("action").trim(), data, function(res) {
         messages.reset();
         if (typeof res.error == "object" && res.error.filter) {
            messages.concat(res.error.map(getErrorMessageObj));
         }
         if (typeof res.errors == "object" && res.errors.filter) {
            messages.concat(res.errors.map(getErrorMessageObj));
         }
         if (messages.messages.length) {
            route.formError(res);
         } else {
            messages.concat([{
               text: "Your information has been updated!",
               type: "success"
            }]);
            route.formSuccess(res);
         }
         messages.render();
      });
      return false;
   });
   $("body").on("submit", "form[method=redirect][action]", function() {
      var $form = $(this).is("form") ? $(this) : $(this).closest("form");
      var data = parseDataArray($form.serializeArray());
      var url = (new Ractive({
         template: $form.attr("action"),
         data: data
      })).toHTML();
      setRouteSafe(router, url);
      return false;
   });

   $overlay = $("<div>", {
      class: "overlay",
      css: {
         "display": "none"
      }
   }).click(function() {
      if ($('.collapse.in').length > 0) {
         $('.navbar-toggle').click();
      }
   });

   $overlay.prependTo("html");

   $('.navbar-toggle').click(function() {
      if ($('.collapse.in').length > 0) {
         $overlay.hide();
      } else {
         $overlay.show();
      }
   });

   $("body").on("keyup", "textarea", function() {
      $(this).height(1);
      $(this).height(this.scrollHeight);
   });
});
