<?php

if ($post && count($post)) {
   
} else if (isset($token)) {
   $db->query("SELECT COUNT(id) > 0 FROM token WHERE id = ?", array($token));
}
