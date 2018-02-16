<?php

require_once 'vendor/autoload.php';

$db = \Social\Social::getDb();

$db->createDb();
$user = new \Social\Entity\User($db);
$user->createTable();
