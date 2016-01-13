# TokenManager.js

Simple helper for storing and retrieving an auth token from the API

### Usage:
```javascript
var token = new TokenManager("namespace");
```

*Set the token*
```javascript
token.set("superlongtokengoeshere");
```

*Get the token*
```javascript
token.get();
// => "superlongtokengoeshere"
```

*Retrieve auth token from API*
```javascript
token.auth({
  teamnum: 4534,
  username: "someusername",
  password: "somepassword"
}).then(function(res) {
  if (token.get()) {
    // passed
    console.log('token is', res);
  } else {
    // failed
    console.log(res);
  }
});
```
