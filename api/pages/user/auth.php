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
      $organization = $dbh->query("SELECT id FROM organization WHERE organization_number = ? AND organization_type = ?", array($team_num, "FRC"));
      $organization_id = 0;
      if (is_array($organization) && count($organization)) {
         $organization_id = $organization[0]["id"];
      } else {
         $errors[] = "Invalid team number";
      }
   }
   if (strlen($username) && strlen($password) && $organization_id > 0) {
      $users = new Auth($dbh, $organization_id);
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
      $output["user"] = $user;
   }
} else {
   $output = array("success" => false, "error" => array($required_fields_err));
}
