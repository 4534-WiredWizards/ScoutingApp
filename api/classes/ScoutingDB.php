<?php
require_once("DBHandler.php");
// Helper class for accessing team/scouting domain specific information/rows
class ScoutingDB {
   public $dbh = NULL;
   public $organization_id = 0;
   public $organization_domain_id = 0;
   public $user_id = 0;
   public function __construct($dbh, $organization_id, $organization_domain_id, $user_id = 0) {
      $this->dbh = $dbh;
      $this->organization_id = $organization_id;
      $this->organization_domain_id = $organization_domain_id;
      $this->user_id = $user_id;
   }
   public function getList($table, $sort_col = "id", $sort_dir = "up", $page = 0, $limit = 100, $fields = NULL, $safe_fields = false) {
      $table_whitelist = array("team", "organization_user");
      if (!in_array($table, $table_whitelist)) return array();
      if (is_null($fields)) {
         $fields = "t.*";
         $safe_fields = true;
      }
      $fields = DBHandler::createFieldString($fields, "t", $safe_fields);
      $sort_dir = ($sort_dir == "up") ? "ASC" : "DESC";
      $limit = DBHandler::createLimitString($page, $limit);
      $where = array();
      $where["organization_id"] = $this->organization_id;
      if ($table !== "organization_user") {
         $where["organization_domain_id"] = $this->organization_domain_id;
      }
      $where = DBHandler::createWhereString($where, "t");
      return $this->dbh->query("SELECT $fields FROM `$table` t WHERE {$where[0]} ORDER BY t.`$sort_col` $sort_dir LIMIT $limit", $where[1]);
   }
   public function getNumPages($table, $limit = 100) {
      $res = $this->dbh->query("SELECT COUNT(t.id) as count FROM `$table` t");
      return ceil($res[0]["count"] / $limit);
   }
   public function getItem($table, $where = array(), $fields = NULL, $safe_fields = false) {
      $table_whitelist = array("team", "organization_user");
      if (!in_array($table, $table_whitelist)) return array();
      if (is_null($fields)) {
         $fields = "t.*";
         $safe_fields = true;
      }
      $where["organization_id"] = $this->organization_id;
      if ($table !== "organization_user") {
         $where["organization_domain_id"] = $this->organization_domain_id;
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
         "weaknesses" => "",
         "score" => 50,
         "scores_json" => "{}",
         "questions_json" => "[]",
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
         INSERT INTO team (
            organization_id,
            organization_domain_id,
            team_number,
            team_name,
            team_type,
            summary,
            score,
            strengths,
            weaknesses,
            questions_json,
            scores_json,
            use_markdown,
            active,
            date_added
         ) VALUES (
            :organization_id,
            :organization_domain_id,
            :team_number,
            :team_name,
            :team_type,
            :summary,
            :score,
            :strengths,
            :weaknesses,
            :questions_json,
            :scores_json,
            TRUE,
            TRUE,
            NOW()
         )
      ";
      $data["organization_id"] = $this->organization_id;
      $data["organization_domain_id"] = $this->organization_domain_id;
      $res = $this->dbh->query($query, $data);
      return $this->getItem("team", array(
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
         "organization_id" => $this->organization_id,
         "organization_domain_id" => $this->organization_domain_id,
         "team_number" => $team_number
      );
      $where_q = DBHandler::createWhereString($where, "e");
      $allowed_fields = array(
         "team_name",
         "summary",
         "strengths",
         "weaknesses",
         "score",
         "questions_json",
         "scores_json",
         "use_markdown",
      );
      $set_data = array();
      foreach($allowed_fields as $field) {
         if (in_array($field, $allowed_fields) && isset($data[$field])) {
            $set_data[$field] = $data[$field];
         }
      }
      $set_q = DBHandler::createSetString($set_data, "e");
      $query = "UPDATE team e SET {$set_q[0]} WHERE {$where_q[0]}";
      $this->dbh->query($query, array_merge($set_q[1], $where_q[1]));
      return $this->getItem("team", array(
         "team_number" => $team_number
      ));
   }
}
