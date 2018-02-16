<?php

/**
 * @file
 * test.php
 */

require_once 'vendor/autoload.php';

use Social\Social;

$db = Social::getDb();
$user = new \Social\Entity\User($db);
//$foo = $user->createEntity();

Kint::dump($user->loadMultiple(["977fa56e-403c-4b5e-bc7a-3744adea937b"]));

