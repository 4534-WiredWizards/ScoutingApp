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

##### Example error response:
```json
{
  "status": "200 OK",
  "success": false,
  "data": [],
  "error": [
    {
      "field": "lastname",
      "msg": "Last Name is required"
    },
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
   - `token` (str)

### `api/user/:userID/edit`
Update a user's information
- `method`: `POST`
- `fields`:
- `token` (str)

##### Example request data:
```json
```

##### Example success response:
```json
```

##### Example error response:
```json
```

------

- `api/users`
   - `intent`: Get all users
   - `method`: `GET`
   - `fields`:
      - `token` (str)


##### Example request data:
```json
```

##### Example success response:
```json
```

##### Example error response:
```json
```

------

- `api/team/:teamID`
   - `intent`: Get a team's information
   - `method`: `GET`
   - `fields`:
      - `token` (str)
   - `api/user/:teamID/edit`
      - `intent`: Update a team's information
      - `method`: `POST`
      - `fields`:
         - `token` (str)


##### Example request data:
```json
```

##### Example success response:
```json
```

##### Example error response:
```json
```

------

- `api/teams`
   - `intent`: Get all teams
   - `method`: `GET`
   - `fields`:
      - `token` (str)
   - `respose`:
      - `data` (arr):
         - `id` (int)
         - `team_number` (int)
         - `team_name` (str)
         - `team_type` (str "FRC")

##### Example request data:
```json
```

##### Example success response:
```json
```

##### Example error response:
```json
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
