<?php

require_once("DBHandler.php");

// Helper class for accessing team/scouting domain specific information/rows
class ScoutingDB {
   public $dbh = NULL;
   public $team_id = 0;
   public $scouting_domain_id = 0;
   public $user_id = 0;

   public function __construct($dbh, $team_id, $scouting_domain_id, $user_id = 0) {
      $this->dbh = $dbh;
      $this->team_id = $team_id;
      $this->scouting_domain_id = $scouting_domain_id;
      $this->user_id = $user_id;
   }

   public function getList($table, $sort_col = "id", $sort_dir = "up", $page = 0, $limit = 100, $fields = NULL, $safe_fields = false) {
      $table_whitelist = array("scouting_entry", "team_user");

      if (!in_array($table, $table_whitelist)) return array();

      if (is_null($fields)) {
         $fields = "t.*";
         $safe_fields = true;
      }

      $fields = DBHandler::createFieldString($fields, "t", $safe_fields);
      $sort_dir = ($sort_dir == "up") ? "ASC" : "DESC";
      $limit = DBHandler::createLimitString($page, $limit);

      $where = array();
      $where["team_id"] = $this->team_id;
      if ($table !== "team_user") {
         $where["scouting_domain_id"] = $this->scouting_domain_id;
      }
      $where = DBHandler::createWhereString($where, "t");

      return $this->dbh->query("SELECT $fields FROM `$table` t WHERE {$where[0]} ORDER BY t.`$sort_col` $sort_dir LIMIT $limit", $where[1]);
   }

   public function getItem($table, $where = array(), $fields = NULL, $safe_fields = false) {
      $table_whitelist = array("scouting_entry", "team_user");

      if (!in_array($table, $table_whitelist)) return array();

      if (is_null($fields)) {
         $fields = "t.*";
         $safe_fields = true;
      }

      $where["team_id"] = $this->team_id;
      if ($table !== "team_user") {
         $where["scouting_domain_id"] = $this->scouting_domain_id;
      }

      $fields = DBHandler::createFieldString($fields, "t", $safe_fields);
      $where = DBHandler::createWhereString($where, "t");

      $query = "SELECT $fields FROM $table WHERE $where";
      $res = $this->dbh->query("SELECT $fields FROM `$table` t WHERE {$where[0]}", $where[1]);
      return isset($res[0]) ? $res[0] : array();
   }

   public function addTeam($data) {
      $default_fields = array(
         "team_number" => 0,
         "team_name" => "",
         "team_type" => "FRC",
         "summary" => "",
         "strengths" => "",
         "weaknesses" => ""
      );
      $team_data = array_merge($default_fields, $data);
      if (!$team_data["team_number"]) {
         return array();
      }
      $data = array();
      foreach(array_keys($default_fields) as $field) {
         $data[$field] = $team_data[$field];
      }
      $query = "
         INSERT INTO
         scouting_entry

         (team_id, scouting_domain_id, team_number, team_name, team_type, summary, strengths, weaknesses, use_markdown, active, date_added)
         VALUES
         (:team_id, :scouting_domain_id, :team_number, :team_name, :team_type, :summary, :strengths, :weaknesses, TRUE, TRUE, NOW())
      ";
      $data["team_id"] = $this->team_id;
      $data["scouting_domain_id"] = $this->scouting_domain_id;
      $this->dbh->query($query, $data);
      return $this->getItem("scouting_entry", array(
         "team_number" => $data["team_number"]
      ));
   }

   public function updateTeam($team_number, $data) {
      if (!($team_number > 0)) {
         return array(
            "error" => "Invalid team number"
         );
      }
      $where = array(
         "team_id" => $this->team_id,
         "scouting_domain_id" => $this->scouting_domain_id,
         "team_number" => $team_number
      );
      $where_q = DBHandler::createWhereString($where, "e");
      $allowed_fields = array(
         "team_name",
         "summary",
         "strengths",
         "weaknesses",
         "use_markdown"
      );
      $set_data = array();
      foreach($allowed_fields as $field) {
         if (in_array($field, $allowed_fields) && isset($data[$field])) {
            $set_data[$field] = $data[$field];
         }
      }
      $set_q = DBHandler::createSetString($set_data, "e");
      $query = "UPDATE scouting_entry e SET {$set_q[0]} WHERE {$where_q[0]}";
      $this->dbh->query($query, array_merge($set_q[1], $where_q[1]));
      return $this->getItem("scouting_entry", array(
         "team_number" => $team_number
      ));
   }
}
