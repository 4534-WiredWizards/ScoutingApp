<?php

// Add team route (api/team/new)

global $dbh;
// Auth user
$user = TeamUsers::authAPICall($dbh);
// Initialize scouting db
$sdb = new ScoutingDB($dbh, $user["team_id"], 1, $user["id"]);

$required_fields = array(
   "team_number" => "Team Number",
   "team_name" => "Team Name",
);
$other_fields = array(
   "weaknesses",
   "summary",
   "strengths",
   "questions_json",
   "scores_json",
   "score",
);

$errors = array();
$success = true;


if (isset($post) && count($post) && $_SERVER["REQUEST_METHOD"] == "POST") {
   $team_data = array();
   foreach($required_fields as $field => $label) {
      if (isset($post[$field]) && strlen(trim($post[$field]))) {
         $team_data[$field] = trim($post[$field]);
      } else {
         $errors[] = array("field" => $field, "msg" => "$label is required");
         $success = false;
      }
   }
   foreach($other_fields as $field) {
      if (isset($post[$field]) && strlen(trim($post[$field]))) {
         $team_data[$field] = trim($post[$field]);
      }
   }

   if ($success) {
      $existing = $sdb->getItem("scouting_entry", array(
         "team_number" => $team_data["team_number"]
      ));
      if (count($existing)) {
         $success = false;
         $errors[] = array("field" => "team_number", "msg" => "Team #{$team_data["team_number"]} already in use!");
      }
   }

   if ($success) {
      $data = $sdb->addTeam($team_data);
      if (!isset($data["id"]) || !$data["id"]) {
         $success = false;
         $data = array();
         $errors[] = "Database error";
      }
   }
}

$output = array(
   "data" => $data,
   "error" => $errors,
   "success" => $success
);
