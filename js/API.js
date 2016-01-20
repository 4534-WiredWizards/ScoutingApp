var API = {
   ajax: function(url, method, data, callback) {
      var data = data || {};
      if (window.token && window.token.get && (data.token === undefined || data.token === null)) {
         data.token = window.token.get();
      }
      return $.ajax({
         url: "api/" + url,
         method: method || "GET",
         data: data
      })
      .error(function(res) {
         if (res.status == 401) alert("need new token")
         return;
      })
      .done(callback || Function())
   },
   get: function(url) {
      var args = Array.prototype.slice.call(arguments).slice(1);
      args = [url, "GET"].concat(args);
      this.ajax.apply(this, args);
   },
   post: function(url) {
      var args = Array.prototype.slice.call(arguments).slice(1);
      args = [url, "POST"].concat(args);
      this.ajax.apply(this, args);
   },
};
