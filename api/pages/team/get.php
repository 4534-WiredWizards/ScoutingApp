<?php

// List teams route (api/team/)

global $dbh;
// Auth user
$user = TeamUsers::authAPICall($dbh);
// Initialize scouting db
$sdb = new ScoutingDB($dbh, $user["team_id"], 1, $user["id"]);

// Default team fields
$default_fields = array(
   "id",
   "team_number",
   "team_name",
   "team_type",
   "summary",
   "strengths",
   "weaknesses",
   "use_markdown",
   "date_added"
);


$options = array_merge(array(
   "fields" => $default_fields,
   "query" => ""
), $get);

$safe_fields = $options["fields"] === $default_fields;

// Output results
$output = array("data" => $sdb->getItem("scouting_entry", array(
   "team_number" => $data["teamID"]
), $options["fields"], $safe_fields));
