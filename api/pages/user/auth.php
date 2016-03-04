<?php

$required_fields_err = "You must use POST method with fields `username`, `password`, and `teamnum`";
if (is_array($post) && count($post)) {
   $success = false;
   $username = $post["username"];
   $password = $post["password"];
   $team_num = (int) $post["teamnum"];
   $errors = array();

   global $dbh;
   if ($team_num > 0) {
      $organization = $dbh->query("
         SELECT id,
                organization_name,
                organization_number,
                config_json
         FROM organization
         WHERE organization_number = ?
         AND organization_type = ?
      ", array(
         $team_num, "FRC"
      ));

      if (is_array($organization) && count($organization)) {
         $organization = $organization[0];
         $organization["config"] = json_decode($organization["config_json"], 1);
      } else {
         $organization = array();
         $errors[] = "Invalid team number";
      }
   }

   if (strlen($username) && strlen($password) && count($organization)) {
      $users = new Auth($dbh, $organization["id"]);
      $user = $users->authUsernamePassword($username, $password);
      if (is_array($user)) {
         if (isset($user["error"])) {
            // Inactive user, etc.
            $errors[] = $user["error"];
         } else {
            $success = true;
            $token = Token::create($dbh, $user["id"]);
         }
      } else {
         $errors[] = "Invalid username/password";
      }
   } else {
      $errors[] = $required_fields_err;
   }

   $output = array();
   $output["success"] = $success;
   $output["error"] = $errors;

   if (strlen($token)) {
      $output["token"] = $token;
      $output["data"] = array(
         "user" => $user,
         "organization" => $organization
      );
   }
} else {
   $output = array(
      "success" => false,
      "error" => array($required_fields_err)
   );
}
