var TokenManager = (function() {
   function TokenManager(ns) {
      this.ns = ns || "";
   }

   TokenManager.prototype.get = function() {
      return localStorage.getItem(this.ns+".token") || "";
   }

   TokenManager.prototype.set = function(token) {
      return localStorage.setItem(this.ns+".token", token || "");
   }

   TokenManager.prototype.auth = function(data) {
      var _this = this;

      return $.ajax({
         url: "api/auth",
         data: data,
         method: "POST"
      }).then(function(res) {
         if (res.success && res.token) {
            _this.set(res.token);
         }
         return res;
      });
   }

   return TokenManager;
})();
