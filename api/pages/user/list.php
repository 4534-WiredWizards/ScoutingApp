<?php

// List users route (api/user/)

global $dbh;
// Auth user
$user = Auth::authAPICall($dbh);
// Initialize scouting db
$sdb = new ScoutingDB($dbh, $user["organization_id"], 1, $user["id"]);

// Default user fields
$default_fields = array(
   "id",
   "username",
   "firstname",
   "lastname",
   "active",
   "date_added"
);


$options = array_merge(array(
   "sort_col" => "id",
   "sort_dir" => "up",
   "page" => 0,
   "limit" => 100,
   "fields" => $default_fields
), $get);

$safe_fields = ($options["fields"] === $default_fields);

// Output results
$output = array(
   "data" => $sdb->getList("organization_user", $options["sort_col"], $options["sort_dir"], $options["page"], $options["limit"], $options["fields"], $safe_fields),
   "numPages" => $sdb->getNumPages("organization_user", $options["limit"])
);
