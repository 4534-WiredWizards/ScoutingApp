var API = {
   baseUrl: "http://maj-daniel.majanit.com/projects/ScoutingApp2016/api/", // needs trailing backslash
   ajax: function(url, method, data, callback) {
      var data = data || {};
      if (window.token && window.token.get && (data.token === undefined || data.token === null)) {
         data.token = window.token.get();
      }
      data.debug = "";
      return $.ajax({
         url: this.baseUrl + url,
         method: method || "GET",
         data: data
      })
      .error(function(res) {
         if (res.status == 401) {
            if (window.token && window.token.get && window.token.get()) {
               window.token.set("");
            }
            router.setRoute("signin");
            return;
         }
         return res;
      })
      .then(function(res) {
         return res;
      })
      .done(callback || Function())
   },
   get: function(url) {
      var args = Array.prototype.slice.call(arguments).slice(1);
      args = [url, "GET"].concat(args);
      return this.ajax.apply(this, args);
   },
   post: function(url) {
      var args = Array.prototype.slice.call(arguments).slice(1);
      args = [url, "POST"].concat(args);
      return this.ajax.apply(this, args);
   },
};
