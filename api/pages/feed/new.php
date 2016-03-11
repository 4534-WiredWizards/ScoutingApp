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
   global $_FILES;
   $id = $sdb->addFeedEntry($data);
   if ($id) {
      $entry = $sdb->getItem("feed_entry", array(
         "id" => $id
      ));

      $user = $sdb->getItem("organization_user", array(
         "id" => $entry["organization_user_id"]
      ), array(
         "firstname",
         "lastname"
      ));
      $entry["organization_user"] = $user["firstname"] . " " . $user["lastname"];

      if (strlen($entry["filename"])) {
         global $api_dir;
         rename("$api_dir/feed_files/files/last-{$entry["filename"]}", "$api_dir/feed_files/files/$id-{$entry["filename"]}");
      }

      $output = array(
         "success" => true,
         "data" => $entry
      );
   } else {
      $output = array(
         "success" => false,
         "error" => array(
            "Server Error"
         )
      );
   }
} else {
   $output = array(
      "success" => false,
      "errors" => array(
         "You must enter the entry text"
      )
   );
}
