<?php

require_once("libs/password.php");

$pass = password_hash("test", PASSWORD_BCRYPT);

echo "UPDATE team_user SET password = '$pass';";
