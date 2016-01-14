# WW Scouting API
-------------
You can expect every API call to output valid JSON.

## (Planned) API Calls

### API Authentication (`api/auth` or `api/authenticate`)

- `api/auth`
   - `method`: `POST`
   - `fields`:
      - `teamnum` (`int`)
      - `username` (`string`)
      - `password` (`string`)
   - `response`:

Example request data:
```json
{
   "teamnum": 4534,
   "username": "someuser",
   "password": "somepass"
}
```

Example success response:
```json
{
  "status": "200 OK",
  "success": true,
  "error": [],
  "token": "bf0417770848bf7b44bb30abfacdcebe"
}
```

Example error response:
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
1. As a header:
```
Authorization: Bearer superlongtokengoeshere
```

2. Using `t` or `token` GET parameter:
```
/api/some-api-call?t=superlongtokengoeshere
/api/some-api-call?token=superlongtokengoeshere
```


------

### Register a user (`api/register`)

- `api/register` (complete!)
   - `method`: `POST`
   - `fields`:
      - `token` (str)
      - `teamnum` (int): Team Number
      - `firstname` (str): First Name
      - `lastname` (str): Last Name
      - `username` (str): Username
      - `password` (str): Password
      - `passconf` (str): Confirm Password
   - `response`:
      - `success` (bool)
      - `error` (arr)
      - `token` (str)



- `api/feed`, `api/user/:userID/feed`, `api/team/:teamID/feed`
   - `intent`: Get a feed of recent events
   - `method`: `GET`
   - `fields`:
      - `token` (str)
   - `response`:
      - `data`:
         - `id` (int)
         - `user` (obj)
         - `text` (str) (md?)
         - `link` (str)

- `api/user/:userID`
   - `intent`: Get a user's information
   - `method`: `GET`
   - `fields`:
      - `token` (str)
   - `api/user/:userID/edit`
      - `intent`: Update a user's information
      - `method`: `POST`
      - `fields`:
         - `token` (str)

- `api/users`
   - `intent`: Get all users
   - `method`: `GET`
   - `fields`:
      - `token` (str)

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



## Key Tables

- `team` - The team that you authenticate under (e.g. *4534*). Parent for `team_user`, `scouting_domain`, `scouting_entry`, and `feed_entry`.
- `scouting_domain` - Container for separating scouting results for years or events (e.g. *Palmetto Regional* or *2016 Season*). Parent for `scouting_entry`, and `feed_entry`.
- `scouting_entry` - Stores scouting information about a team.
   - `team_id`
   - `scouting_domain_id`
   - `id`

- `feed_entry` - Stores an entry for the `team` or `scouting_domain` feeds.
