<?php

// Get user route (api/user/:userID )

global $dbh;
// Auth user
$user = OrgUsers::authAPICall($dbh);
// Initialize scouting db
$sdb = new ScoutingDB($dbh, $user["team_id"], 1, $user["id"]);

$default_fields = array(
   "id",
   "firstname",
   "lastname",
   "username",
   "active"
);

$options = array_merge(array(
   "fields" => $default_fields
), $get);

$safe_fields = ($options["fields"] === $default_fields);

$where = array();
if (is_numeric($data["userID"])) {
   $where["id"] = $data["userID"];
} else {
   $where["username"] = $data["userID"];
}

$output = array(
   "data" => $sdb->getItem("team_user", $where, $options["fields"], $safe_fields)
);

if (!is_array($output["data"]) || !count($output["data"])) {
   $output["data"] = array();
   $output["status"] = "404 Not Found";
   $output["error"] = array(
      "User not found"
   );
}
