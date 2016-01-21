<?php

// Helper class for including and rendering pages/api routes
class Display {
   public $not_found = "not-found";

   // Construct with pages array and an output callback
   public function __construct($pages = array(), $output_fn = NULL) {
      $this->pages = $pages;
      if (is_callable($output_fn)) {
         $this->output_fn = $output_fn;
      }
   }

   // Call output callback with args
   public function render($page, $args = array()) {
      if (!isset($this->pages[$page])) $page = $this->not_found;
      $result = call_user_func($this->output_fn, $this->pages[$page], $args, $page);
      return $result;
   }
}
