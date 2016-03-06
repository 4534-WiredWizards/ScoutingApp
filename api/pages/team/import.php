<?php

// Import teams route (api/team/import)
// Import teams from a tba event code (e.g. "2016ncral")

global $dbh;
// Auth user
$user = Auth::authAPICall($dbh);
// Initialize scouting db
$sdb = new ScoutingDB($dbh, $user["organization_id"], 1, $user["id"]);
$tba = new TBA();

require(__DIR__."/default-fields.php");

$default_fields = $output["fields"];

$required_fields = array(
   "event_code" => "Event Code",
);
$other_fields = array(
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
      $teams = $tba->get("event/{$team_data["event_code"]}/teams");
      if (is_array($teams) && count($teams)) {
         foreach($teams as $team) {
            $existing = $sdb->getItem("team", array(
               "team_number" => $team["team_number"]
            ), array("id"));
            if (!$existing) {
               $sdb->addTeam(array_merge($default_fields, array(
                  "team_number" => $team["team_number"],
                  "team_name" => $team["nickname"]
               )));
            }
         }
      } else {
         $data = array();
         $errors[] = "Invalid event code \"{$team_data["event_code"]}\"";
         $success = false;
      }
   }
}

$output = array(
   "data" => $data,
   "error" => $errors,
   "success" => $success
);
