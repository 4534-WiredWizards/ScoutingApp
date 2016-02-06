function setLoggedin(loggedIn) {
   var method = loggedIn ? "addClass" : "removeClass";
   if (document.readyState === "complete") {
      $("body")[method]("loggedin");
   } else {
      $(document).ready(function() {
         $("body")[method]("loggedin");
      });
   }
}
// Initialize token manager
var token = new TokenManager("ww-scouting", setLoggedin.bind(this, true), setLoggedin.bind(this, false));
// Bootstrap .alert manager
var messages = new MessageManager(".alerts", []);

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
routes.register("/invite", {
   template: "templates/user/invite.html",
   init: function() {
      this.updateTitle("Invite");
   },
   formSuccess: function(res) {
      router.setRoute("user/"+res.data.id);
   },
   requireSignin: true
});
routes.register("/signin", {
   template: "templates/signin.html",
   init: function() {
      this.updateTitle("Sign In");
   },
   formSuccess: function(res) {
      if (res.success && res.token) {
         token.set(res.token);
         router.setRoute(routes.defaultUrl);
      }
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
      router.setRoute("team/"+res.data.team_number);
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
      setTimeout(function() {
         ractive.set("score", 50)
      }, 100);
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
