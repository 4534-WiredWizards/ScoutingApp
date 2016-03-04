<?php

// Add team route (api/team/new)

global $dbh;
// Auth user
$user = Auth::authAPICall($dbh);
// Initialize scouting db
$sdb = new ScoutingDB($dbh, $user["organization_id"], 1, $user["id"]);

$required_fields = array(
   "team_name" => "Team Name",
);
$other_fields = array(
   "weaknesses",
   "summary",
   "score",
   "strengths",
   "questions_json",
   "scores_json",
);

$errors = array();
$success = true;

if (isset($post) && count($post) && $_SERVER["REQUEST_METHOD"] == "POST") {
   $team_data = array();
   foreach($required_fields as $field => $label) {
      if (isset($post[$field])) {
         if (strlen(trim($post[$field]))) {
            $team_data[$field] = trim($post[$field]);
         } else {
            $errors[] = array("field" => $field, "msg" => "$label can't be blank");
            $success = false;
         }
      }
   }
   foreach($other_fields as $field) {
      if (isset($post[$field])) {
         $team_data[$field] = trim($post[$field]);
      }
   }

   if ($success) {
      $existing = $sdb->getItem("team", array(
         "team_number" => $data["teamID"]
      ));
      if (!count($existing)) {
         $success = false;
         $errors[] = array("field" => "team_number", "msg" => "Team #{$data["teamID"]} doesn't exist!");
      }
   }

   if ($success) {
      $data = $sdb->updateTeam($data["teamID"], $team_data);
      if (!isset($data["id"]) || !$data["id"] && !$data["error"]) {
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
