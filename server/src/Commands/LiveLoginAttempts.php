<?php

namespace Social\Commands;

use Social\Entity\Logs;
use Social\Entity\User;
use Social\EntityBase;
use Social\Social;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * CLI command to see live failed login attempts.
 */
class LiveLoginAttempts extends Command {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('social:login_attempts')
      ->setDescription('See live failed login attempts')
      ->setHelp('See in live the failing login attempts')
      ->setAliases(['lm']);
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $db = Social::getDb();
    $tabble = $db->getTable('logs');

    $cursor = $tabble->changes()->run($db->getConnection());

    foreach ($cursor as $row) {
      \Kint::dump($row);
    }
  }

}
