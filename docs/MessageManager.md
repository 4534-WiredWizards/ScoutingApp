# [MessageManager.js](https://github.com/4534-WiredWizards/ScoutingApp2016/blob/master/js/MessageManager.js)

Helper class for displaying user error/success messages with [bootstrap alerts](http://getbootstrap.com/components/#alerts)

### Usage

#### Initialize
```javascript
// new MessageManager(selector, initialMessages);
var messages = new MessageManager(".alerts", []);
```

#### reset
```javascript
messages.reset(doRender);
```

#### Render
```javascript
messages.render();
```

#### Render Individual Message
```javascript
var text = "You succeeded!";
var type = "success"; // or "danger" or "info" or "warning"
messages.renderMessage(text, type);
```

##### Message Types ([Glyphicons](http://glyphicons.com/))
* [success](https://raw.githubusercontent.com/4534-WiredWizards/ScoutingApp2016/master/docs/screenshots/success-alert.png?token=AHH6q-LCduttLmkLoDgnvKj7nl91HqVOks5Wt5eNwA%3D%3D)
* [danger](https://raw.githubusercontent.com/4534-WiredWizards/ScoutingApp2016/master/docs/screenshots/danger-alert.png?token=AHH6q563aCNnPRw9yQzctY-kZOqssM67ks5Wt5eMwA%3D%3D)
* [info](https://raw.githubusercontent.com/4534-WiredWizards/ScoutingApp2016/master/docs/screenshots/info-alert.png?token=AHH6q9GoCjJMijPF27FDiIpHQGbaf4Xuks5Wt5eMwA%3D%3D)
* [warning](https://raw.githubusercontent.com/4534-WiredWizards/ScoutingApp2016/master/docs/screenshots/warning-alert.png?token=AHH6q220NN6rXhIzDIwtxG2eAXm3iMJVks5Wt5eOwA%3D%3D)
