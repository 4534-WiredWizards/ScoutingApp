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

   public function getEntries($limit = 100, $sort_col = "team_number", $sort_dir = "up") {
      $sort_dir = ($sort_dir == "up") ? "DESC" : "ASC";
      return $this->dbh->query("SELECT se.* FROM scouting_entry se ORDER BY se.`$sort_col` $sort_dir LIMIT $limit");
   }
}
