<?php

class TBA {
   public $key = "";
   public $cache_dir = "../cache/tba";

   public function __construct($key = "frc4534:scouting:app", $cache_dir = "../cache/tba") {
      $this->key = $key;
      $this->cache_dir = __DIR__."/".$cache_dir;
   }

   public function get($url, $later_than = "-5 minutes") {
      $cached_contents = TBA::cache_get($this->cache_dir, $url, $later_than);
      if ($cached_contents !== FALSE) {
         return $cached_contents;
      }

      $options = array(
         'http' => array(
            'header'  => "X-TBA-App-Id: {$this->key}",
            'method'  => "GET"
         ),
      );

      $context = stream_context_create($options);
      $result = file_get_contents("https://www.thebluealliance.com/api/v2/$url", false, $context);
      $result = json_decode($result, 1);

      if ($cached_contents === FALSE && !is_null($result)) {
         TBA::cache_set($this->cache_dir, $url, $result);
      }

      return $result;
   }

   public static function cache_clean_filename($file = "") {
      $file = preg_replace('/\//', '-', $file);
      $file = preg_replace('/[^A-Za-z0-9-_,\s]/', '', $file);
      $file = preg_replace('/[\s-]+/', '_', $file);
      return $file;
   }
   public static function cache_set($directory, $file, $contents = array()) {
      $file = TBA::cache_clean_filename($file);
      if (!file_exists($directory)) {
         return;
      }
      file_put_contents("$directory/$file.json", json_encode(array(
         "timestamp" => time(),
         "contents" => $contents
      )));
   }
   public static function cache_get($directory, $file, $later_than = "-5 minutes") {
      $file = TBA::cache_clean_filename($file);
      if (!file_exists("$directory/$file.json")) {
         return FALSE;
      }
      $file_contents = json_decode(file_get_contents("$directory/$file.json"), 1);
      if (!count($file_contents)) {
         return FALSE;
      }
      if (!is_integer($later_than)) {
         $later_than = strtotime($later_than);
      }
      if ($later_than > $file_contents["timestamp"]) {
         return FALSE;
      }
      return $file_contents["contents"];
   }
}
