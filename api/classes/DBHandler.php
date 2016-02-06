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
      if (is_array($res)) {
         foreach($res as $key => &$val) {
            if (substr($key, -5) == "_json") {
               $res[substr($key, 0, -5)] = json_decode($val, 1);
            } else if (is_array($val)) {
               foreach($val as $subkey => $subval) {
                  if (substr($subkey, -5) == "_json") {
                     $val[substr($subkey, 0, -5)] = json_decode($subval, 1);;
                  }
               }
            }
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

   static function createWhereString($where = array(), $table_prefix = "") {
      $where_q = "1";
      $operators = array("=", ">=", "<=", "LIKE");
      $fields = array();
      foreach($where as $filter_key => $filter_val) {
         if (is_array($filter_val)) {
            $fields[] = $filter_val["value"];
            if (in_array($filter_val["operator"], $operators)) {
               $op = $filter_val["operator"];
            } else {
               $op = $operators[0];
            }
         } else {
            $fields[] = $filter_val;
            $op = $operators[0];
         }
         $where_q .= " AND `$table_prefix`.`$filter_key` $op ?";
      }
      return array("($where_q)", $fields);
   }

   static function createSetString($data = array(), $table_prefix = "") {
      if (!is_array($data)) {
         return "";
      }
      $set_arr = array();
      $set_vals = array();
      foreach($data as $field => $value) {
         $set_arr[] = "`$table_prefix`.`$field` = ?";
         $set_vals[] = $value;
      }
      return array(implode(", ", $set_arr), $set_vals);
   }
}
