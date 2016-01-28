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
   requireSignin: true
});
routes.register("/signin", {
   template: "templates/signin.html",
   init: function() {
      $(".main-title").html("Sign In");
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
      $(".main-title").html("Add a Team");
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
            }
         }
      })
      ractive.set(data.team);
      $(".main-title").html(new Ractive({
         template: "{{team_type}} Team #{{team_number}} - {{team_name}}",
         data: data.team
      }).toHTML());
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
      $(".main-title").html(new Ractive({
         template: "{{team_type}} Team #{{team_number}} - {{team_name}} - Edit",
         data: data.team
      }).toHTML());
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
      $(this.titleElem).html("Teams");
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
      $(this.titleElem).html("Teams");
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
      before: [routes.destroyExisting.bind(routes), function() {
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

   function parseDataString(str) {
      var result = {};
      var str = str || "";
      str.split("&").forEach(function(part) {
         var item = part.split("=");
         result[item[0]] = decodeURIComponent(item[1]);
      });
      return result;
   }

   $("body").on("submit", "form[method=async][action]", function() {
      var $form = $(this).is("form") ? $(this) : $(this).closest("form");
      var data = parseDataString($form.serialize());

      API.post($form.attr("action").trim(), data, function(res) {
         console.dir(data);
         console.dir(res);
      });
      return false;
   });
   $("body").on("submit", "form[method=redirect][action]", function() {
      var $form = $(this).is("form") ? $(this) : $(this).closest("form");
      var data = parseDataString($form.serialize());
      var url = (new Ractive({
         template: $form.attr("action"),
         data: data
      })).toHTML();
      setRouteSafe(router, url);
      return false;
   });
});
