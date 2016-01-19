<?php

global $dbh;
$user = TeamUsers::authAPICall($dbh);
$sdb = new ScoutingDB($dbh, $user["team_id"], 0, $user["id"]);

$options = array_merge(array(
   "sort_col" => "team_number",
   "sort_dir" => "up",
   "page" => 0,
   "limit" => 100,
   "fields" => null
), $get);

$safe_fields = false;
$output = array("data" => $sdb->getEntries($options["sort_col"], $options["sort_dir"], $options["page"], $options["limit"], $options["fields"], $safe_fields));
