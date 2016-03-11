<?php

// Feed route (api/feed/)

global $dbh;
// Auth user
$user = Auth::authAPICall($dbh);
$sdb = new ScoutingDB($dbh, $user["organization_id"], 1, $user["id"]);

$options = array_merge(array(
   "id" => 0,
   "down" => 0
), $get);

$where = array(
   "id" => $options["id"]
);

$feed_entry = $sdb->getItem("feed_entry", array(
   "id" => $options["id"]
), array(
   "id",
   "entry",
   "filename"
), true);

if (is_array($feed_entry) && isset($feed_entry["id"]) && $feed_entry["id"] > 0 && isset($feed_entry["filename"]) && strlen($feed_entry["filename"])) {
   global $api_dir;
   $filepath = "$api_dir/feed_files/files/{$feed_entry["id"]}-{$feed_entry["filename"]}";
   if (file_exists($filepath)) {
      $file_info = new SplFileInfo($filepath);
      $extension = strtolower($file_info->getExtension());

      $image_extensions = array(
         "jpg",
         "jpeg",
         "png",
         "gif",
      );

      if (in_array($extension, $image_extensions)) {
         $type = "image/$extension";
         header("Content-Type: $type");
         header("Content-Length: " . filesize($filepath));
      } else {
         header('Content-Description: File Transfer');
         header('Content-Type: application/octet-stream');
         header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
         header('Expires: 0');
         header('Cache-Control: must-revalidate');
         header('Pragma: public');
         header('Content-Length: ' . filesize($filepath));
      }
      
      readfile($filepath);
      exit;
   }
}
