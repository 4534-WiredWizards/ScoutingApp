<?php

function require_all($directory) {
   $files = scandir($directory);
   foreach($files as $file) {
      if (pathinfo($file, PATHINFO_EXTENSION) == "php") {
         require_once("$directory/$file");
      }
   }
}

function redirect($url) {
   global $_GET;
   if (isset($_GET['block']) && $_GET['block']) {
      echo "<span class='cblock cblock-block-not-found'>Block not found</span>";
      exit;
   } else {
      header("Location: $url");
   }
}

$base_dir = __DIR__;
