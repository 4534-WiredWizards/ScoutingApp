# WW Scouting API
-------------

## Key Tables

- `team` - The team that you authenticate under (e.g. *4534*). Parent for `team_user`, `scouting_domain`, `scouting_entry`, and `feed_entry`.
- `scouting_domain` - Container for separating scouting results for years or events (e.g. *Palmetto Regional* or *2016 Season*). Parent for `scouting_entry`, and `feed_entry`.
- `scouting_entry` - Stores scouting information about a team.
   - `team_id`
   - `scouting_domain_id`
   - `id`

- `feed_entry` - Stores an entry for the `team` or `scouting_domain` feeds.


## (Planned) API Calls

- `api/auth`
   - `intent`: Provide an API authorization token
   - `method`: `POST`
   - `fields`:
      - `teamnum` (`int`)
      - `username` (`string`)
      - `password` (`string`)

- `api/register`
   - `intent`: Register a new user
   - `method`: `POST`
   - `fields`:
      - `teamnum` (int): Team Number
      - `firstname` (str): First Name
      - `lastname` (str): Last Name
      - `username` (str): Username
      - `password` (str): Password
      - `passconf` (str): Confirm Password
