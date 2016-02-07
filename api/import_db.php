<?php

require_once("libs/password.php");

$pass = password_hash("test", PASSWORD_BCRYPT);

echo "UPDATE organization_user SET password = '$pass';";
