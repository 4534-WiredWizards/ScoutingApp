# WW Scouting API
-------------
You can expect every API call to output valid JSON.

## (Planned) API Calls

### API Authentication (`api/auth` or `api/authenticate`)
- Method: `POST`
- Fields:
   - `teamnum` (`int`)
   - `username` (`string`)
   - `password` (`string`)

##### Example request data:
```
teamnum: 4534
username: "someuser"
password: "somepass"
```
##### Example success response:
```json
{
  "status": "200 OK",
  "success": true,
  "error": [],
  "token": "bf0417770848bf7b44bb30abfacdcebe"
}
```

##### Error Messages:
- `Invalid username/passwword` - *The username/password combination was incorrect for the team*
- `Invalid team number` - *The team number was blank or isn't set up with the scouting API*
- `You must use POST method with fields username, password, and teamnum` - *Invalid HTTP Method or not all required fields were passed*

##### Example error response:
```json
{
  "status": "200 OK",
  "success": false,
  "error": [
    "Invalid username/password"
  ]
}
```


#### Using your token

Once you have acquired your token you will use it in all other API calls (except for `api/register`).

##### Here are the ways to include the token in an API call:
`superlongtokengoeshere` is the token used in these examples.
1. **As a header:**
```
Authorization: Bearer superlongtokengoeshere
```

2. **Using `t` or `token` GET parameter** -
The API call url would be something like this:
```
/api/some-api-call?t=superlongtokengoeshere
/api/some-api-call?token=superlongtokengoeshere
```

------

### Register a user* (`api/register`)

\* This will register a user in the same team that you authenticated with.

- Method: `POST`
- Fields:
   - `token` (`str`)
   - `firstname` (`str`): First Name
   - `lastname` (`str`): Last Name
   - `username` (`str`): Username
   - `password` (`str`): Password
   - `passconf` (`str`): Confirm Password
- Response Fields:
   - `success` (`bool`)
   - `error` (`arr`)
   - `token` (`str`)

##### Example request data:
```
token: "superlongtokenhere"
username: "some-new-user"
password: "fRcr0cks4534"
passconf: "fRcr0cks4534"
firstname: "John"
lastname: "Doe"
```

##### Example success response:
```json
{
  "status": "200 OK",
  "success": true,
  "data": {
    "id": 31415,
    "firstname": "John",
    "lastname": "Doe",
    "username": "some-new-user",
    "active": 1
  },
  "error": []
}
```

##### Error Messages:
- *$field is required:*
```json
{
  "field": "$field",
  "msg": "$fieldlabel is required"
}
```
- *Passwords don't match*
```json
{
  "field": "password",
  "msg": "Password and Confirm Password do not match."
}
```
- *Invalid Username:*
```json
{
  "field": "username",
  "msg": "Usernames can only contain letters, numbers, underscores, and dashes, and must start with a letter."
}
```
- *Username already in use:*
```json
{
  "field": "username",
  "msg": "Username already in use"
}
```

##### Example error response:
```json
{
  "status": "200 OK",
  "success": false,
  "data": [],
  "error": [
    {
      "field": "username",
      "msg": "Username is required"
    }
  ]
}
```

------

### Feeds (`api/feed`, `api/user/:userID/feed`, `api/team/:teamID/feed`)
Get a feed of recent events
- Method: `GET`
- Fields:
   - `token` (`str`)
   - `sort_dir` (`str`) (choices `up`|`down`) (default `up`)

##### Example request data:
```
token: "superlongtokengoeshere"
sort_dir: "up"
```

##### Example success response:
```json
{
  "status": "200 OK",
  "success": true,
  "data": [
    {
      "id": 21212,
      "name": "This person did that thing!",
      "url": "team/4534",
      "entry": "They did this thing at this time! Wow!",
      "use_markdown": 1,
      "date_added": "2015-01-14 18:34:00"
    },
    {
      "id": 12121,
      "name": "This scouter hates team 4534!",
      "url": "team/4534",
      "entry": "Changed hate from 1 to 10",
      "use_markdown": 1,
      "date_added": "2015-01-14 18:30:00"
    },
  ],
  "error": []
}
```

------

### User Information (`api/user/:userID`)
Get a user's information
- Method: `GET`
- Fields:
   - `token` (`str`)

##### Example request data:
```
token: "superlongtokenhere"
```

##### Example success response:
```json
{
  "status": "200 OK",
  "success": true,
  "data": {
    "id": 31415,
    "firstname": "John",
    "lastname": "Doe",
    "username": "john-doe",
    "active": 1
  }
}
```

##### Example error response:
```json
{
  "status": "404 Not Found",
  "success": false,
  "error": [
    "User not found"
  ]
}
```

------

### Modify User (`api/user/:userID/edit`)
Update a user's information
Use `api/user/me/edit` to update the authenticated user's information

- Method: `POST`
- Fields:
   - `token` (`str`)
- Optional Fields:
   - `password` (paired with `passconf`)
   - `passconf` (paired with `password`)
   - `firstname`
   - `lastname`

##### Example request data:
```json
token: "superlongtokengoeshere"
password: "sup3rpa55w0rd"
passconf: "sup3rpa55w0rd"
firstname: "John"
lastname: "Goat"
```

##### Example success response:
```json
{
  "status": "200 OK",
  "success": true,
  "data": {
    "id": 31415,
    "firstname": "John",
    "lastname": "Goat",
    "username": "some-new-user",
    "active": 1
  },
  "error": []
}
```

##### Example error response:
```json
{
  "status": "404 Not Found",
  "success": false,
  "error": [
    "User not found"
  ]
}
```

------

### All Users' Information (`api/users`)

Get all users

- Method: `GET`
- Fields:
   - `token` (str)

##### Example request data:
```
token: "superlongtokengoeshere"
```

##### Example success response:
```json
{
  "status": "200 OK",
  "success": true,
  "data": [
    {
      "id": 31415,
      "firstname": "John",
      "lastname": "Do",
      "username": "some-user",
      "active": 1
    },
    {
      "id": 31416,
      "firstname": "John",
      "lastname": "Don't",
      "username": "another-user",
      "active": 1
    }
  ]
}
```

------

### Team Information (`api/team/:teamID`)
Get a team's information
   - `method`: `GET`
   - `fields`:
      - `token` (str)

##### Example request data:
```
token: "superlongtokengoeshere"
```

##### Example success response:

```json
{
  "status": "200 OK",
  "success": true,
  "data": {
    "id": 1,
    "team_number": 4534,
    "team_name": "Wired Wizards"
    "team_type": "FRC",
    "summary": "summary goes here",
    "strengths": "",
    "weaknesses": "",
    "use_markdown": 1,
    "date_added": "2016-01-17 13:50:00"
  }
}
```

##### Example error response:
```json
{
  "status": "404 Not Found",
  "success": false,
  "error": [
    "Team not found"
  ]
}
```

------
### Add a Team (`api/user/:teamID/new`)

Update a team's information

- Method: `POST`
- Fields:
   - `token` (str)
   - `team_number` (int)
   - `team_name` (str)
- Optional Fields:
   - `summary` (str)
   - `strengths` (str)
   - `weaknesses` (str)
   - `use_markdown` (bool)


##### Example request data:
```
token: "superlongtokengoeshere"
team_number: 4534
team_name: "Wired Wizards"
summary: "Some team"
strengths: ""
weaknesses: ""
use_markdown: 1
```

##### Example success response:
```json
{
  "status": "200 OK",
  "success": true,
  "data": {
    "id": 1,
    "team_number": 4534,
    "team_name": "Wired Wizards",
    "team_type": "FRC",
    "summary": "Some team",
    "strengths": "",
    "weaknesses": "",
    "use_markdown": 1,
    "date_added": "2016-01-17 14:20:00"
  }
}
```


##### Error Messages:
- `team_number is required` - *Team number is required*
- `Invalid team_number` - *Team number isn't a number*
- `team_name is required` - *Team name is required*

##### Example error response:
```json
{
  "status": "404 Not Found",
  "success": false,
  "error": [
    "Team not found"
  ]
}
```
------

### Update Team Information (`api/user/:teamID/edit`)

Update a team's information

- Method: `POST`
- Fields:
   - `token` (str)
- Optional Fields:
   - `team_name` (str)
   - `summary` (str)
   - `strengths` (str)
   - `weaknesses` (str)
   - `use_markdown` (bool)


##### Example request data:
```
token: "superlongtokengoeshere"
team_name: "Wired Wizards in Wilmington"
```

##### Example success response:
```json
{
  "status": "200 OK",
  "success": true,
  "data": {
    "id": 1,
    "team_number": 4534,
    "team_name": "Wired Wizards in Wilmington",
    "team_type": "FRC",
    "summary": "summary goes here",
    "strengths": "",
    "weaknesses": "",
    "use_markdown": 1,
    "date_added": "2016-01-17 13:50:00"
  }
}
```

##### Example error response:
```json
{
  "status": "404 Not Found",
  "success": false,
  "error": [
    "Team not found"
  ]
}
```

------

### Get all teams (`api/teams`)

Get all teams

- Method: `GET`
- Fields:
   - `token` (str)
   - `sort_col` (str)
   - `sort_dir` (str "up"|"down")
- Response Fields:
   - `data` (arr):
      - `id` (int)
      - `team_number` (int)
      - `team_name` (str)
      - `team_type` (str "FRC")
      - `summary` (str)
      - `strengths` (str)
      - `weaknesses` (str)
      - `use_markdown` (bool)
      - `date_added` (str)

##### Example request data:
```
token: "superlongtokengoeshere"
sort_col: "id"
sort_dir: "down"
```

##### Example success response:
```json
{
  "status": "200 OK",
  "success": true,
  "data": [
    {
      "id": 1,
      "team_number": 4534,
      "team_name": "Wired Wizards"
      "team_type": "FRC",
      "summary": "summary goes here",
      "strengths": "",
      "weaknesses": "",
      "use_markdown": 1,
      "date_added": "2016-01-17 13:50:00"
    },
    {
      "id": 2,
      "team_number": 1,
      "team_name": "Juggernauts"
      "team_type": "FRC",
      "summary": "summary goes here",
      "strengths": "",
      "weaknesses": "",
      "use_markdown": 1,
      "active": 1,
      "date_added": "2016-01-17 13:51:00"
    }
  ]
}
```

------

## Key Tables

- `team` - The team that you authenticate under (e.g. *4534*). Parent for `team_user`, `scouting_domain`, `scouting_entry`, and `feed_entry`.
- `scouting_domain` - Container for separating scouting results for years or events (e.g. *Palmetto Regional* or *2016 Season*). Parent for `scouting_entry`, and `feed_entry`.
- `scouting_entry` - Stores scouting information about a team.
   - `team_id`
   - `scouting_domain_id`
   - `id`

- `feed_entry` - Stores an entry for the `team` or `scouting_domain` feeds.
