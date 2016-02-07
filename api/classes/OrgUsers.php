<?php

// Helper class for handling user authentication in an organization.
class OrgUsers {
   public $organization_id = 0;
   public $dbh = NULL;
   public $default_fields = "id, firstname, lastname, username, password, active";

   public function __construct($dbh, $organization_id = 0) {
      $this->dbh = $dbh;
      $this->organization_id = $organization_id;
   }

   public function create($username, $password, $firstname = "", $lastname = "") {
      if (count($this->getByUsername($username, array()))) {
         return array("error" => "Username already in use");
      }
      $insert_q = "
         INSERT INTO organization_user
         ( organization_id,  username,  password,  firstname,  lastname, date_added)
         VALUES
         (:organization_id, :username, :password, :firstname, :lastname, NOW())";

      global $api_dir;
      require_once("$api_dir/libs/password.php");

      $sth = $this->dbh->query($insert_q, array(
         "organization_id" => $this->organization_id,
         "username" => $username,
         "password" => password_hash($password, PASSWORD_BCRYPT),
         "firstname" => $firstname,
         "lastname" => $lastname
      ));

      if (isset($sth["error"]) && strlen($sth["error"])) {
         return array("error" => $sth["error"]);
      }

      return $this->getByUsername($username);
   }

   public function authUsernamePassword($username, $password) {
      global $api_dir;
      require_once("$api_dir/libs/password.php");
      $user = $this->getByUsername($username, array("id", "active", "password"));
      $matches = password_verify($password, $user["password"]);
      if ($matches) {
         if (!$user["active"]) {
            return array("error" => "Inactive user");
         }
         return $user;
      }
   }

   public function getByUsername($username, $fields = NULL, $safe_fields = false) {
      if (is_null($fields)) {
         $fields = $this->default_fields;
         $safe_fields = true;
      }
      $fields = DBHandler::createFieldString($fields, "", $safe_fields);
      $res = $this->dbh->query("SELECT $fields FROM organization_user WHERE organization_id = ? AND username = ?", array(
         $this->organization_id,
         $username
      ));
      if (is_array($res) && count($res)) {
         return $res[0];
      }
   }

   public function getByID($id, $fields = NULL, $safe_fields = false) {
      if (is_null($fields)) {
         $fields = $this->default_fields;
         $safe_fields = true;
      }
      $fields = DBHandler::createFieldString($fields, "", $safe_fields);
      $res = $this->dbh->query("SELECT $fields FROM organization_user WHERE organization_id = ? AND id = ?", array(
         $this->organization_id,
         $id
      ));
      if (is_array($res) && count($res)) {
         return $res[0];
      }
   }

   public function getAll($limit = 100, $fields = NULL, $safe_fields = false) {
      if (is_null($fields)) {
         $fields = "id, firstname, lastname, username, active";
         $safe_fields = true;
      }
      $limit = (int) $limit;
      return $this->dbh->query("SELECT $fields FROM organization_user WHERE organization_id = ? LIMIT $limit", array(
         $this->organization_id
      ));
   }

   static function authAPICall($dbh, $output_on_error = true, $output_type = "json") {
      require_once("Token.php");
      $token_data = Token::check($dbh, Token::getToken());
      if (isset($token_data["organization_user_id"])) {
         $user = $dbh->query("SELECT * FROM organization_user WHERE id = ?", array($token_data["organization_user_id"]));
         if (count($user)) {
            $user = $user[0];
            $user["token_data"] = $token_data;
            return $user;
         }
      }
      if ($output_on_error) {
         $status = "401 Unauthorized";
         output($output_type, array(
            "status" => $status,
            "success" => false,
            "error" => array(
               "Invalid token"
            )
         ), $status);
         exit;
      }
   }
}
