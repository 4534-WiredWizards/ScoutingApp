<?php

$required_fields = array(
   "teamnum" => "Team Number",
   "firstname" => "First Name",
   "lastname" => "Last Name",
   "username" => "Username",
   "password" => "Password",
   "passconf" => "Confirm Password"
);

$errors = array();
$success = true;

if (isset($post) && count($post) && $_SERVER["REQUEST_METHOD"] == "POST") {
   $user_data = array();
   foreach($required_fields as $field => $label) {
      if (isset($post[$field]) && strlen(trim($post[$field]))) {
         $user_data[$field] = trim($post[$field]);
      } else {
         $errors[] = array("field" => $field, "msg" => "$label is required");
         $success = false;
      }
   }

   if ($success && $user_data["password"] != $user_data["passconf"]) {
      $errors[] = array("field" => "password", "msg" => "{$required_fields['password']} and {$required_fields['passconf']} do not match.");
      $success = false;
   }

   if (strlen($user_data["username"]) && !preg_match("/^[a-zA-Z][\w-_]*\$/", $user_data["username"])) {
      $errors[] = array("field" => "username", "msg" => "Usernames can only contain letters, numbers, underscores, and dashes, and must start with a letter.");
      $success = false;
   }

   if ($success) {
      global $dbh;
      $users = new TeamUsers($dbh, $user_data["teamnum"]);
      if ($users->getByUsername($user_data["username"])) {
         $success = false;
         $errors[] = array("field" => "username", "msg" => "Username already in use");
      } else {
         $res = $users->create($user_data["username"], $user_data["password"], $user_data["firstname"], $user_data["lastname"]);
         if (isset($res["error"]) && strlen($res["error"])) {
            $errors[] = "SQL Error: \"".$res["error"]."\"";
            $success = false;
         } else {
            $data = array_merge($res, array(
               "token" => Token::create($dbh, $user_data["teamnum"])
            ));
         }
      }
   }
   
}

$output = array(
   "data" => $data,
   "error" => $errors,
   "success" => $success
);
