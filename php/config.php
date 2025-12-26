<?php

$config = array(
    "host" => "localhost",
    "user" => "root",
    "dbname" => "dolphin_crm",
    "dbpassword" => ""
);

$pdo = new PDO('mysql:host='.$config['host'].';dbname='.$config['dbname'], $config['user'], $config['dbpassword']);

?>