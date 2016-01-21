<?php

// http://php.net/manual/en/class.pdo.php
class DBHandler extends PDO {
   // Cacheable prepared queries
   public $prepared_queries = array();

   public function __construct($config = array()) {
      // Connect to database
      $dns = "mysql" . ':host=' . $config['host'] . ';dbname=' . $config['database'];
      parent::__construct($dns, $config['username'], $config['password']);
   }

   public function prepare($query, $opts = array()) {
      if (!isset($this->prepared_queries[$query])) {
         // Cache prepared statement
         $this->prepared_queries[$query] = parent::prepare($query, $opts);
      }
      // Return existing prepared statement
      return $this->prepared_queries[$query];
   }

   public function query($query, $data = array()) {
      // Helper for querying database
      $sth = $this->prepare($query);
      $sth->execute($data);
      $res =  $sth->fetchAll(PDO::FETCH_ASSOC);
      if (is_array($res) && !count($res)) {
         $err = $sth->errorInfo();
         if (strlen($err[2])) {
            return array("error" => $err[2]);
         } else {
            return array();
         }
      }
      return $res;
   }

   static function createFieldString($fields = array(), $table_prefix = "", $safe_fields = FALSE) {
      // Helper for creating an SQL field string:
      // this::createFieldString(array("id", "name")); //=> "`id`, `name`";
      if (strlen($table_prefix)) $table_prefix .= ".";
      if (is_array($fields) && !count($fields)) return "1";
      if ($safe_fields) {
         if (is_array($fields)) {
            $fields = implode(", ", $fields);
         }
      } else {
         if (is_string($fields)) {
            $fields = preg_split("/\\s*,\\s*/", preg_replace("/`/", "", $fields));
         }
         if (is_array($fields)) {
            $fields = "$table_prefix`" . implode("`, $table_prefix`", $fields) . "`";
         } else {
            return "1";
         }
      }
      return $fields;
   }

   static function createLimitString($page = 0, $limit = 100) {
      // Helper for creating SQL limit with paging
      $page = (int) $page;
      $limit = (int) $limit;
      return ($page * $limit) . ", " . ($limit);
   }
}
