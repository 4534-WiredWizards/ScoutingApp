/**
 * Helper class for storing and retrieving the token
 * @class
 */
var TokenManager = (function() {
   /**
    * Constructor
    * @example https://github.com/4534-WiredWizards/ScoutingApp2016/blob/master/docs/TokenManager.md#initialize-new-token
    *
    * @param string ns The key you would like to store the token in in localStorage
    */
   function TokenManager(ns, hasTokenCallback, noTokenCallback) {
      this.ns = ns || "token";
      this.hasTokenCallback = (hasTokenCallback || Function()).bind(this);
      this.noTokenCallback = (noTokenCallback || Function()).bind(this);
      if (this.get()) {
         this.hasTokenCallback();
      } else {
         this.noTokenCallback();
      }
      this.user = false;
   }

   /**
    * Get the token from localStorage
    * @example https://github.com/4534-WiredWizards/ScoutingApp2016/blob/master/docs/TokenManager.md#set-the-token
    */
   TokenManager.prototype.get = function() {
      return localStorage.getItem(this.ns) || "";
   }

   /**
    * Store the token in localStorage
    * @example https://github.com/4534-WiredWizards/ScoutingApp2016/blob/master/docs/TokenManager.md#get-the-token
    *
    * @param string token The token you would like to store
    */
   TokenManager.prototype.set = function(token, user) {
      var token = token || "";
      var curToken = this.get();
      this.user = false;
      if (token && user) {
         this.user = user;
      }
      res = localStorage.setItem(this.ns, token);
      if (curToken && !token) {
         this.noTokenCallback();
      } else if (!curToken && token) {
         this.hasTokenCallback();
      }
      return res;
   }

   /**
    * Make an AJAX call to the API and handle the response
    * @example https://github.com/4534-WiredWizards/ScoutingApp2016/blob/master/docs/TokenManager.md#retrieve-auth-token-from-api
    *
    * @param mixed data The data you are sending to the API
    */
   TokenManager.prototype.auth = function(data) {
      var _this = this;

      /*
      Make an ajax call to the api with the provided data
      The data can be in one of the following formats:
      As a string: "teamnum=4534&username=someuser&password=somepass"
      As JSON: {"teamnum":4534,"username":"someuser","password":"somepass"}
      */
      API.post("auth", data, function(res) {
         // Save the token if the user is authenticated
         if (res.success && res.token) {
            _this.set(res.token, res.user);
         }
         return res;
      });
   }

   return TokenManager;
})();
