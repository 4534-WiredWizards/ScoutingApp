<?php

// Feed route (api/feed/)

global $dbh;
// Auth user
$user = OrgUsers::authAPICall($dbh);
$sdb = new ScoutingDB($dbh, $user["organization_id"], 1, $user["id"]);

$default_fields = array(
   "id",
   "organization_user_id",
   "name",
   "url",
   "entry",
   "use_markdown"
);


$options = array_merge(array(
   "sort_col" => "date_added",
   "sort_dir" => "up",
   "page" => 0,
   "limit" => 20,
   "fields" => $default_fields
), $get);

$safe_fields = $options["fields"] === $default_fields;

// Output results
$output = array(
   "data" => $sdb->getList("feed_entry", $options["sort_col"], $options["sort_dir"], $options["page"], $options["limit"], $options["fields"], $safe_fields),
   "numPages" => $sdb->getNumPages("feed_list", $options["limit"])
);
