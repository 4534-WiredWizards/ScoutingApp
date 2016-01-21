<?php

// List teams route (api/team/)

global $dbh;
// Auth user
$user = TeamUsers::authAPICall($dbh);
// Initialize scouting db
$sdb = new ScoutingDB($dbh, $user["team_id"], 0, $user["id"]);

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
   "sort_col" => "team_number",
   "sort_dir" => "up",
   "page" => 0,
   "limit" => 100,
   "fields" => $default_fields
), $get);

$safe_fields = $options["fields"] === $default_fields;

// Output results
$output = array("data" => $sdb->getList("scouting_entry", $options["sort_col"], $options["sort_dir"], $options["page"], $options["limit"], $options["fields"], $safe_fields));
