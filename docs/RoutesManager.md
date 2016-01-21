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
  init: function() {
    alert("Route opened");
  },
  destroy: function() {
    alert("Leaving route");
  },
  template: "templates/some-template.html"
});
```

#### Get and use director.js compatible routes object
```javascript
var router = new Router(routes.getObject());
```
