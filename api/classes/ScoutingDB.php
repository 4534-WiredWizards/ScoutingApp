<?php

require_once("DBHandler.php");

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
      return $this->dbh->query("SELECT $fields FROM `$table` t ORDER BY t.`$sort_col` $sort_dir LIMIT $limit");
   }

   public function getTeams() {

   }
}
