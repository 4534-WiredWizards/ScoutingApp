<?php

global $dbh;
$user = TeamUsers::authAPICall($dbh);
$sdb = new ScoutingDB($dbh, $user["team_id"], 0, $user["id"]);
