<?php

class Router {
   public function __construct($routes = array()) {
      $this->routes = $routes;
   }

   public function find_match($paths, $method = "GET") {
      $method = strtoupper($method);

      $orig_paths = $paths;
      $default_route_config = array(
         "extra_params" => array()
      );

      $result = false;
      $params = array();
      $paths = $this->get_paths($paths);

      foreach($this->routes as $orig_route_paths => $route_config) {
         if (is_array($route_config) && isset($route_config['type']) && $route_config['type'] == 'methods') {
            $route_config = $route_config['methods'][$method];
         }
         if (!is_array($route_config)) {
            $route_config = array("page" => $route_config);
         }
         $route_config = array_merge($default_route_config, $route_config);

         $route_paths = $this->get_paths($orig_route_paths);
         $route_match = $this->route_matches($route_paths, $paths);
         if ($route_match !== FALSE) {
            $params = array_merge($params, $route_config['extra_params'], $route_match);
            $result = $route_config;
            $result['route_paths'] = $orig_route_paths;
            break;
         }
      }
      return array(
         "route" => $result,
         "data" => $params,
         "url" => $orig_paths,
      );
   }

   public function route_matches($route_paths, $paths) {
      $ends_on_array = substr($route_paths[count($route_paths)-1], -2) == '[]';
      if (count($route_paths) != count($paths) && !$ends_on_array) return FALSE;

      $params = array();
      $passes = TRUE;
      foreach($route_paths as $i => $route_path) {
         $url_path = $paths[$i];
         if ($route_path[0] == ':') {
            $key = substr($route_path, 1);
            if ($ends_on_array && $i == count($route_paths) - 1) {
               $key = substr($key, 0, -2);
               if (!isset($params[$key])) $params[$key] = array();
               for($k = $i; $k < count($paths); $k++) {
                  $url_path = $paths[$k];
                  $params[$key][] = $url_path;
               }
            } else {
               $params[$key] = $url_path;
            }
         } else if ($route_path != $url_path) {
            $passes = FALSE;
            break;
         }
      }
      return $passes === TRUE ? $params : FALSE;
   }

   public function get_paths($path) {
      return explode('/', trim($path, '/'));
   }
}
