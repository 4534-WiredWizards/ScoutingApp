<?php

$api_dir = __DIR__;
require_once("$api_dir/helpers.php");
require_all("$base_dir/classes");

require("$api_dir/pages/team/default-fields.php");
$default_fields = $output["fields"];

$user = array(
   "organization_id" => 1,
   "id" => 1
);

$dbh = new DBHandler(json_decode(file_get_contents("$api_dir/dbconfig.json"), 1));
$sdb = new ScoutingDB($dbh, $user["organization_id"], 1, $user["id"]);

$tba = new TBA();

$file = $argv[1];

if (!file_exists($file)) {
   die("$file does not exist");
}

$data = array(
   "stats" => array(
      "defenses" => json_decode(file_get_contents($file), 1)
   )
);

$team_stats = array();

foreach($data["stats"] as $stat => $teams) {
   if (is_string($teams)) {
      $teams = json_decode($teams, 1);
   }
   foreach($teams as $team_number => $stats) {
      if (!isset($team_stats[$team_number])) {
         $team_stats[$team_number] = array();
      }
      $team_stats[$team_number][$stat] = $stats;
   }
}

$json_fields = array(
   "questions",
   "stats",
);

foreach($team_stats as $team_number => $stats) {
   $existing = $sdb->getItem("team", array(
      "team_number" => $team_number
   ), array("id", "stats_json"));
   if ($existing["id"] > 0) {
      $sdb->updateTeam($team_number, array_merge($existing, array(
         "stats" => $stats
      )));
   } else {
      $tba_team = $tba->get("team/frc$team_number");
      $data = array_merge($default_fields, $tba_team, array(
         "team_number" => $team_number,
         "team_name" => $tba_team["nickname"],
         "stats" => $stats
      ));
      foreach($json_fields as $field) {
         if (isset($data[$field])) {
            $data["{$field}_json"] = json_encode($data[$field]);
         }
      }
      $sdb->addTeam($data);
   }
}


die(print_r($team_stats));
