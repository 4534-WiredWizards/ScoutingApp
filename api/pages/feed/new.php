<?php

// Add new feed route (api/feed/new)

global $dbh;
// Auth user
$user = Auth::authAPICall($dbh);
$sdb = new ScoutingDB($dbh, $user["organization_id"], 1, $user["id"]);

global $_POST;

$data = array_merge(array(
   "entry" => "",
   "url" => ""
), $_POST, array(
   "organization_user_id" => $user["id"]
));

if (strlen($data["entry"])) {
   $id = $sdb->addFeedEntry($data);
   $output = array(
      "success" => true,
      "data" => $sdb->getItem("feed_entry", array(
         "id" => $id
      ))
   );
} else {
   $output = array(
      "success" => false,
      "errors" => array(
         "You must enter the entry text"
      )
   );
}
