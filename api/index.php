<?php

global $base_dir;
$api_dir = __DIR__;

if (isset($_GET['debug'])) {
   ini_set("display_errors",1);
   error_reporting(-1);
}
if (isset($_GET['headers'])) {
   die(print_r($headers));
}

// Allow other domains to connect to api (e.g. 4534-WiredWizards.github.io or localhost)
header('Access-Control-Allow-Origin: *');

function clean_data($data, $levels = 5) {
   if ($levels > 0 && is_array($data) && count($data)) {
      foreach($data as $key => &$row) {
         if ($key === "password") {
            $row = "****";
         } else if (is_array($row)) {
            // recursion!
            $row = clean_data($row, $levels-1);
         }
      }
   }
   return $data;
}

function output($type = "json", $data = array(), $status_code = "200 OK") {
   $fn = "output_$type";
   $fn($data, $status_code);
}
function output_json($data, $status_code) {
   global $_GET;
   header("HTTP/1.0 $status_code");
   $data = clean_data($data);
   if (isset($_GET['callback'])) {
      // JSONP
      header("Content-Type: application/javascript");
      echo "{$_GET['callback']}(" . json_encode($data) . ");";
   } else {
      header("Content-Type: application/json");
      echo json_encode($data);
   }
}

require_once("$api_dir/helpers.php");
require_all("$base_dir/classes");
require("$api_dir/routes.php");

$dbh = new DBHandler(json_decode(file_get_contents("$api_dir/dbconfig.json"), 1));

#die(Token::create($dbh, 1, array(1,2,4)));

if (!isset($_GET['q']) || !strlen($_GET['q'])) {
   $_GET['q'] = "index";
}

$route_match = $router->find_match($_GET['q']);

$args = array(
   "get" => $_GET,
   "path" => $route_match['url'],
   "data" => $route_match['data'],
);

$headers = getallheaders();
if (isset($headers['Content-Type']) && $headers['Content-Type'] == 'application/json') {
   $raw_data = file_get_contents("php://input");
   try {
      $args['post'] = json_decode($raw_data, 1);
   } catch (Exception $e) {
      output("json", "Invalid JSON", "400 Bad Request");
   }
} else {
   $args['post'] = $_POST;
}

$display = new Display($pages, function($page, $args, $page_key) use ($pages) {
   extract($args, EXTR_SKIP);
   if (file_exists($page->script)) {
      require($page->script);
   } else {
      require($pages["not-found"]->script);
   }
   if (gettype($output) !== 'array') {
      $output = array(
         "message" => $output
      );
   }
   if (isset($success) && !isset($output['success'])) {
      $output['success'] = $success;
   }
   if (isset($status) && !isset($output['status'])) {
      $output['status'] = $status;
   }

   if (!isset($output['success'])) {
      $output['success'] = TRUE;
   }
   if (!isset($output['status'])) {
      $output['status'] = "200 OK";
      if (!isset($output['success'])) {
         $output['success'] = TRUE;
      }
   }

   // Move status and success to front of output
   $output = array_merge(array(
      "status" => $output['status'],
      "success" => $output['success']
   ), $output);

   output("json", $output, $output['status']);
});

echo $display->render($route_match['route']['page'], $args);
