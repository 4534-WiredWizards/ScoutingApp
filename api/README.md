# WW Scouting API
-------------

## Key Tables

- `team` - The team that you authenticate under (e.g. *4534*). Parent for `team_user`, `scouting_domain`, `scouting_entry`, and `feed_entry`.
- `scouting_domain` - Container for seperating scouting results for years or events (e.g. *Palmetto Regional* or *2016 Season*). Parent for `scouting_entry`, and `feed_entry`.
- `scouting_entry` - Stores scouting information about a team.
   - `team_id`
   - `scouting_domain_id`
   - `id`
   
   
- `feed_entry` - Stores an entry for the `team` or `scouting_domain` feeds.
