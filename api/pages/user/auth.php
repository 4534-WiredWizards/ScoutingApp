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
      $team = $dbh->query("SELECT id FROM team WHERE team_number = ? AND team_type = ?", array($team_num, "FRC"));
      $team_id = 0;
      if (is_array($team) && count($team)) {
         $team_id = $team[0]["id"];
      } else {
         $errors[] = "Invalid team number";
      }
   }
   if (strlen($username) && strlen($password) && $team_id > 0) {
      $users = new TeamUsers($dbh, $team_id);
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
   }
} else {
   $output = array("success" => false, "error" => array($required_fields_err));
}
