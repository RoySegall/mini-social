<?php

require 'vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

$commands = \Social\Social::parseYaml(file_get_contents('commands.yml'));

foreach ($commands['commands'] as $namespace) {
  $application->add(new $namespace);
}

$application->run();
