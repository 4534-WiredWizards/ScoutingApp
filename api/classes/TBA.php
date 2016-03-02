<?php

class TBA {
   public $key = "";
   public function __construct($key = "frc4534:scouting:app") {
      $this->key = $key;
   }
   public function get($url) {
      $options = array(
         'http' => array(
            'header'  => "X-TBA-App-Id: {$this->key}",
            'method'  => "GET"
         ),
      );

      $context  = stream_context_create($options);
      $result = file_get_contents("https://www.thebluealliance.com/api/v2/$url", false, $context);
      return json_decode($result, 1);
   }
}
