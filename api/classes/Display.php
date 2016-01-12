<?php

class Display {
   public $not_found = "not-found";

   public function __construct($pages = array(), $output_fn = NULL) {
      $this->pages = $pages;
      if (is_callable($output_fn)) {
         $this->output_fn = $output_fn;
      }
   }

   public function render($page, $args = array()) {
      if (!isset($this->pages[$page])) $page = $this->not_found;
      if (isset($this->output_fn) && is_callable($this->output_fn)) {
         $result = call_user_func($this->output_fn, $this->pages[$page], $args, $page);
      } else {
         $class = "cblock-" . trim(preg_replace("/[^a-zA-Z0-9]+/", "-", $page), "-");
         $result = $this->pages[$page]->render($args, $class);
      }
      return $result;
   }
}
