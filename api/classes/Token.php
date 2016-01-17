<?php

class Token {
   public static function check($dbh, $token, $fields = NULL, $safe_fields = false) {
      if (is_null($fields)) {
         $fields = "team_user_id, data";
         $safe_fields = true;
      }
      $fields = $dbh->createFieldString($fields, "", $safe_fields);

      $res = $dbh->query("SELECT $fields FROM auth_token WHERE date_expires >= NOW() AND active IS TRUE AND token = ?", array($token));

      if (count($res)) {
         return $res[0];
      }

      return false;
   }

   public static function create($dbh, $team_user_id, $data = "", $expire_time = 12 /*Hours*/) {
      $token = md5(uniqid($team_user_id, true));
      $expire_time = (float) $expire_time;
      $dbh->query("INSERT INTO auth_token (team_user_id, token, data, date_added, date_expires) VALUES (:team_user_id, :token, :data, NOW(), DATE_ADD(NOW(), INTERVAL $expire_time HOUR))", array(
         "team_user_id" => $team_user_id,
         "token" => $token,
         "data" => (is_string($data)) ? $data : json_encode($data)
      ));
      return $token;
   }

   // Get token from get parameter or `Authorization` header
   public static function getToken() {
      global $args;
      extract($args);
      $headers = getallheaders();
      if (is_array($get) && isset($get["token"])) {
         return $get["token"];
      } else if (is_array($post) && isset($post["token"])) {
         return $post["token"];
      } else if (isset($headers["Authorization"])) {
         preg_match("/[\\w]{10,32}/", $headers["Authorization"], $matches);
         if (count($matches)) {
            return $matches[0];
         }
      }
   }
}
