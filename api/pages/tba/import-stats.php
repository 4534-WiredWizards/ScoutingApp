<?php

// Get tba result (api/tba/import-stats)

global $dbh;
// Auth user
$user = Auth::authAPICall($dbh);
// Initialize scouting db
$sdb = new ScoutingDB($dbh, $user["organization_id"], 1, $user["id"]);

$tba = new TBA();

$data = array_merge(array(
   "stats" => array()
), $_POST);

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

$output = array("data" => $team_stats);
