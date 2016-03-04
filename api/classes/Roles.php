<?php

class RoleManager {
   public $roles = array();
   public $actions = array();
   public $user_permissions = array();

   public function __construct($roles = array(), $actions = array(), $user_roles = array("default")) {
      if (is_string($user_roles)) {
         $user_roles = preg_split('/\s*,\s*/', '', $user_roles);
      }
      foreach($user_roles as $role_key) {
         if (isset($roles[$role_key])) {
            $this->user_permissions = array_merge($this->user_permissions, $roles[$role_key]);
         }
      }
   }

   public function can_do($action, $context = array()) {
      if (isset($this->user_permissions[$action])) {
         if (is_callable($this->user_permissions[$action])) {
            $this->user_permissions[$action]($context);
         } else {
            return $this->user_permissions[$action];
         }
      }
   }
}
