<?php

class TeamUsers {
   public $team_id = 0;
   public $dbh = NULL;
   public $default_fields = "id, firstname, lastname, username, password, active";

   public function __construct($dbh, $team_id = 0) {
      $this->dbh = $dbh;
      $this->team_id = $team_id;
   }

   public function create($username, $password, $firstname = "", $lastname = "") {
      if (count($this->getByUsername($username, array()))) {
         return array("error" => "Username already in use");
      }
      $insert_q = "
         INSERT INTO team_user
         ( team_id,  username,  password,  firstname,  lastname, date_added)
         VALUES
         (:team_id, :username, :password, :firstname, :lastname, NOW())";

      global $api_dir;
      require_once("$api_dir/libs/password.php");

      $sth = $this->dbh->query($insert_q, array(
         "team_id" => $this->team_id,
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
      $user = $this->getByUsername($username, array("active", "password"));
      $matches = ($user["password"] == password_hash($password, PASSWORD_BCRYPT));
      if ($matches && !$user["active"]) {
         return array("error" => "Inactive user");
      }
      return $matches;
   }

   public function getByUsername($username, $fields = NULL, $safe_fields = false) {
      if (is_null($fields)) {
         $fields = $this->default_fields;
         $safe_fields = true;
      }
      $fields = $this->dbh->createFieldString($fields, "", $safe_fields);
      $res = $this->dbh->query("SELECT $fields FROM team_user WHERE team_id = ? AND username = ?", array(
         $this->team_id,
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
      $fields = $this->dbh->createFieldString($fields, "", $safe_fields);
      $res = $this->dbh->query("SELECT $fields FROM team_user WHERE team_id = ? AND id = ?", array(
         $this->team_id,
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
      return $this->dbh->query("SELECT $fields FROM team_user WHERE team_id = ?", array(
         $this->team_id
      ));
   }
}

