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
var routes = new RoutesManager([], "", "teams", token);

ractiveMethods = ({
   setParam: (function(key, value) {
      var params = getParams();
      params[key] = value;
      setHashParams(params);
   }),
   setParams: setHashParams,
});

function setHashParams(params) {
   window.location.hash = window.location.hash.split("?").concat("")[0] + "?" + $.param(params);
}

function RactiveCustom(config, data, defaultParams) {
   var config = $.extend({
      el: ".main",
      template: data.template
   }, ractiveMethods, config);

   ractive = new Ractive(config);

   ractive.set({
      params: getParams(defaultParams || {}, window.location.hash)
   });

   return ractive;
}

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
      ractive = RactiveCustom({
         data: {
            action: "team/new",
            scores: {
               "Spy": 50,
               "Defense": 50,
               "Assist": 50,
               "Shoot": 50,
            },
            questions: {
               "can you do this thing?": false,
               "can you do this other thing?": true
            },
            score: 50
         },
         computed: {
            scores_json: function() {
               return JSON.stringify(this.get('scores'));
            },
            questions_json: function() {
               return JSON.stringify(this.get('questions'));
            },
         }
      }, data);
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
      ractive = RactiveCustom({
         data: {
            splitLine: function(val) {
               return (val || "").split(/\s*\n\s*/g).filter(Boolean);
            },
            score: 0,
            showChart: false
         },
         computed: {
            scores_json: function() {
               return JSON.stringify(this.get('scores'));
            },
            questions_json: function() {
               return JSON.stringify(this.get('questions'));
            },
         }
      }, data);
      delete data.team.scores_json;
      delete data.team.questions_json;

      ractive.set(data.team);
      if (typeof data.team.scores === "object") {
         ractive.set("showChart", true);
         buildBarGraph('data-table');
      }
      this.updateTitle("{{team_type}} Team #{{team_number}} - {{team_name}}", data.team);

      var score = data.team.score;
      if (score > 0) {
         ractive.set("score", 0);
         setTimeout(function() {
            ractive.set("score", score);
         }, 100);
      }
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
      ractive = RactiveCustom({
         data: {
            splitLine: function(val) {
               return (val || "").split(/\s*\n\s*/g).filter(Boolean);
            },
            score: 0
         }
      }, data);
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
      ractive = RactiveCustom({
         data: {
            action: "user/"+userID+"/edit"
         }
      }, data);
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
      ractive = RactiveCustom({
         data: {
            action: "team/"+teamNum+"/edit"
         },
         computed: {
            scores_json: function() {
               return JSON.stringify(this.get('scores'));
            },
            questions_json: function() {
               return JSON.stringify(this.get('questions'));
            },
         }
      }, data);
      delete data.team.scores_json;
      delete data.team.questions_json;
      ractive.set(data.team);
      this.updateTitle("{{team_type}} Team #{{team_number}} - {{team_name}} - Edit", data.team);
   },
   requireSignin: true
});

function getParams(params, str) {
   var str = str || window.location.hash;
   str = str.split("?").concat(["",""])[1];
   var params = typeof params == "object" ? params : {};
   str
      .replace(/(^\?)/,'')
      .split("&")
      .filter(Boolean)
      .forEach(function(param) {
         var param = param.split("=").concat("");
         var value = param[1];
         if (Number(value) == value) {
            value = Number(value);
         }
         params[param[0]] = value;
      });
   return $.extend({}, params);
}

var teamParams = ({
   page: 1,
   limit: 10,
   // q: ""
});
routes.register("/team", {
   template: "templates/team/list.html",
   dataCallbacks: {
      teams: function(_this, callback, config) {
         var params = getParams(teamParams);
         API.get("team", params, function(res) {
            res.data.numPages = res.numPages;
            callback(res.data);
         });
      }
   },
   init: function(data) {
      ractive = RactiveCustom({
         data: data
      }, data, teamParams);
      this.updateTitle("Teams");
   },
   requireSignin: true
});

routes.register("/user", {
   template: "templates/user/list.html",
   dataCallbacks: {
      users: function(_this, callback) {
         API.get("user", {}, function(res) {
            res.data.numPages = res.numPages;
            console.log(res.data)
            callback(res.data);
         });
      }
   },
   init: function(data) {
      ractive = RactiveCustom({
         data: data
      }, data);
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
      ractive = RactiveCustom({
         data: {
            teams: data.teams,
            query: query
         }
      }, data);
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

routes.register("/tba", {
   template: "templates/tba.html",
   init: function(data) {
      ractive = RactiveCustom({
         data: {
            rows: [],
            displayField: function(field) {
               return field.replace(/[_]/g, " ").replace(/\s{2,}/g, " ").split(" ").map(function(word) {
                  return word.charAt(0).toUpperCase() + word.slice(1);
               }).join(" ");
            }
         },
      }, data);
      ractive.on("submit", function() {
         var _this = this;
         this.set("rows", []);
         $.ajax({
            dataType: "json",
            url: "https://www.thebluealliance.com/api/v2/" + this.get("url"),
            data: "",
            headers: {
               'X-TBA-App-Id': 'frc4534:scouting-app:testing'
            }
         }).then(function(res) {
            if (!res.length) {
               var res = [res];
            }
            _this.set("rows", res);
         });
      });
      this.updateTitle("TBA");
   }
});
