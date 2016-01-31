var MessageManager = (function() {
   var icons = {
      danger: "exclamation-sign",
      success: "ok-sign",
      info: "info-sign",
      warning: "warning-sign"
   };

   function MessageManager(el, messages) {
      var _this = this;
      this.$el = $("<div />");
      if (document.readyState == "complete") {
         $(el).append(this.$el);
      } else {
         $(document).ready(function() {
            $(el).append(_this.$el);
         });
      }
      this.messages = [];
      this.addMessages(messages);
      this.render();

      var $button = $("<button>", {
         type: "button",
         class: "close",
         "data-dismiss": "alert",
         "aria-label": "Close",
         html: "<span aria-hidden='true'>&times;</span>"
      });

      this.$baseAlert = $("<div />", {
         role: "alert",
         text: "",
         class: "alert alert-dismissable"
      })
      .append('<span><span class="glyphicon"></span>&nbsp;</span>')
      .append('<span class="text"></span>')
      .append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
   }

   MessageManager.prototype.addMessages = function(newMessages) {
      this.messages = this.messages.concat(newMessages)
      return this;
   }

   MessageManager.prototype.removeByKey = function(key) {
      var ind = this.messages.indexOf((this.messages.filter(function(message) {
         return message.key === key;
      }) || [])[0]);
      if (ind > -1) {
         this.messages.splice(ind, 1);
      }
   }

   MessageManager.prototype.render = function() {
      var _this = this;
      _this.$el.find(".alert").remove();
      _this.messages.forEach(function(message) {
         if (!message) {
            return;
         }
         if (!message.key) {
            message.key = Symbol();
         }
         var type = "success",
             text = "";
         if (typeof message == "string") {
            text = message;
         } else {
            if (message.type) {
               type = String(message.type);
            }
            if (message.text) {
               text = String(message.text);
            }
         }
         if (type && text) {
            _this.renderMessage(text, type, message.key);
         }
      });
      return _this;
   }

   MessageManager.prototype.renderMessage = function(text, type, key) {
      var _this = this;
      var type = type || "success";
      var $message = this.$baseAlert.clone().addClass("alert-"+type);
      $message.find(".text").text(text);
      $message.find(".glyphicon").addClass("glyphicon-"+icons[type]);
      $message.find(".close").data("key", key);
      $message.find(".close").click(function() {
         _this.removeByKey($(this).data("key"));
      });
      $message.appendTo(this.$el);
      return $message;
   }

   MessageManager.prototype.reset = function(render) {
      this.messages = [];
      if (render) {
         this.render();
      }
      return this;
   }

   return MessageManager;
})();
