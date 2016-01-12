<?php

$pages = array(
   "index" => new Page("pages/index.php"),
   "register" => new Page("pages/register.php"),
   "auth" => new Page("pages/auth.php"),
   "authenticate" => new Page("pages/auth.php"),
   "not-found" => new Page("pages/not-found.php"),
);

$routes = array(
);

$page_keys = array();
foreach($pages as $page => $page_obj) {
   $page_keys["$page"] = $page;
}

$routes = array_merge($page_keys, $routes);

foreach($routes as &$route) {
   if (gettype($route) !== 'array') {
      $route = array(
         "display_page" => FALSE,
         "page" => $route
      );
   }
}

$router = new Router($routes);
