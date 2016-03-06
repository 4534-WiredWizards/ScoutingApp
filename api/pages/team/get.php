<?php

// List teams route (api/team/)

global $dbh;
// Auth user
$user = Auth::authAPICall($dbh);
// Initialize scouting db
$sdb = new ScoutingDB($dbh, $user["organization_id"], 1, $user["id"]);

// Default team fields
$default_fields = array(
   "id",
   "team_number",
   "team_name",
   "team_type",
   "summary",
   "score",
   "strengths",
   "weaknesses",
   "questions_json",
   "scores_json",
   "stats_json",
   "use_markdown",
   "date_added"
);


$options = array_merge(array(
   "fields" => $default_fields,
   "query" => ""
), $get);

$safe_fields = $options["fields"] === $default_fields;

// Output results
$output = array("data" => $sdb->getItem("team", array(
   "team_number" => $data["teamID"]
), $options["fields"], $safe_fields));
