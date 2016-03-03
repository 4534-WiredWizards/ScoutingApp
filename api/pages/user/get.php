<?php

// Get user route (api/user/:userID )

global $dbh;
// Auth user
$user = Auth::authAPICall($dbh);
// Initialize scouting db
$sdb = new ScoutingDB($dbh, $user["organization_id"], 1, $user["id"]);

function array_pluck($array = array(), $keys = array(), $default_values = array()) {
   $result = array();
   $array = array_merge($default_values, $array);
   foreach($keys as $key) {
      $result[$key] = isset($array[$key]) ? $array[$key] : NULL;
   }
   return $result;
}

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

$default_values = array(
   "id" => 0,
   "firstname" => "",
   "lastname" => "",
   "username" => "",
);

$safe_fields = ($options["fields"] === $default_fields);

$where = array();
if (is_numeric($data["userID"])) {
   $where["id"] = $data["userID"];
} else {
   $where["username"] = $data["userID"];
}

$output = array(
   "data" => $sdb->getItem("organization_user", $where, $options["fields"], $safe_fields)
);

$output["data"] = array_pluck($output["data"], $options["fields"], $default_values);

if (!is_array($output["data"]) || !count($output["data"])) {
   $output["data"] = array();
   $output["status"] = "404 Not Found";
   $output["error"] = array(
      "User not found"
   );
}
