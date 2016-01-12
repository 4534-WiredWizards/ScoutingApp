<?php

class Page {
   public function __construct($script, $display_block = TRUE) {
      $this->script = $script;
      $this->display_block = $display_block;
   }

   public function render($args, $class="") {
      extract($args);
      $output = "";
      if (file_exists("{$this->script}")) {
         require("{$this->script}");
      } else {
         $output = "Page not found";
      }
      if ($this->display_block) {
         return "<span class='cblock $class'>$output</span>";
      } else {
         return $output;
      }
   }
}
