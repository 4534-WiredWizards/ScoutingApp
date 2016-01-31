# [RoutesManager.js](https://github.com/4534-WiredWizards/ScoutingApp2016/blob/master/js/RoutesManager.js)

Helper class for registering routes for director

### Usage

#### Initialize
```javascript
var routes = new RoutesManager();
```

#### Register a route
```javascript
routes.register("/some-route", {
  elem: ".main",
  init: function(data) {
    alert("Route opened");
    console.log(data.someData)
  },
  destroy: function() {
    alert("Leaving route");
  },
  formSubmit: function() { alert("A form was submitted") },
  formSuccess: function(res) { alert("A form was submitted and the API response was successful") },
  formError: function(res) { alert("A form was submitted and the API response was unsuccessful") },
  template: "templates/some-template.html",
  dataCallbacks: {
    // Methods to retrieve data from the API asynchronously
    someData: function(_this, callback) {
      API.get("some/api/call", {}, function(res) {
         callback(res.data);
      });
    }
  },
  requireSignin: true // or false
});
```

#### Get and use director.js compatible routes object
```javascript
var router = new Router(routes.getObject());
```
