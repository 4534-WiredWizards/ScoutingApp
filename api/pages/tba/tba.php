<?php

// Get tba result (api/tba)

global $dbh;
// Auth user
$user = OrgUsers::authAPICall($dbh);
// Initialize scouting db
$sdb = new ScoutingDB($dbh, $user["organization_id"], 1, $user["id"]);

$tba = new TBA();

$output["data"] = $tba->get($_GET["url"], $_GET["later_than"]);
