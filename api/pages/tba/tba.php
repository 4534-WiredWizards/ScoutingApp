<?php

// Get tba result (api/tba)

global $dbh;
// Auth user
$user = Auth::authAPICall($dbh);
// Initialize scouting db
$sdb = new ScoutingDB($dbh, $user["organization_id"], 1, $user["id"]);

$tba = new TBA();

$data = array_merge(array(
   "url" => "",
   "later_than" => ""
), $_GET);

$output["data"] = $tba->get($data["url"], $data["later_than"]);
