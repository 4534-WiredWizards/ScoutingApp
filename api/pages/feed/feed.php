<?php

// Feed route (api/feed/)

global $dbh;
// Auth user
$user = Auth::authAPICall($dbh);
$sdb = new ScoutingDB($dbh, $user["organization_id"], 1, $user["id"]);

$default_fields = array(
   "id",
   "organization_user_id",
   "name",
   "url",
   "entry",
   "filename",
   "use_markdown",
   "date_added"
);

$options = array_merge(array(
   "sort_col" => "date_added",
   "sort_dir" => "down",
   "page" => 1,
   "limit" => 20,
   "fields" => $default_fields,
   "url" => ""
), $get);

$safe_fields = $options["fields"] === $default_fields;

$where = array();
if (strlen($options["url"])) {
   $where["url"] = $options["url"];
}

// Output results
$output = array(
   "data" => $sdb->getList("feed_entry", $options["sort_col"], $options["sort_dir"], $options["page"], $options["limit"], $options["fields"], $safe_fields, $where),
   "numPages" => $sdb->getNumPages("feed_entry", $options["limit"], $where)
);

foreach($output["data"] as &$row) {
   $user = $sdb->getItem("organization_user", array(
      "id" => $row["organization_user_id"]
   ), array(
      "firstname",
      "lastname"
   ));
   $row["organization_user"] = $user["firstname"] . " " . $user["lastname"];
}
