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
   function TokenManager(ns) {
      this.ns = ns || "token";
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
   TokenManager.prototype.set = function(token) {
      return localStorage.setItem(this.ns, token || "");
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
      return $.ajax({
         url: "api/auth",
         data: data,
         method: "POST"
      }).then(function(res) {
         // Save the token if the user is authenticated
         if (res.success && res.token) {
            _this.set(res.token);
         }
         return res;
      });
   }

   return TokenManager;
})();
