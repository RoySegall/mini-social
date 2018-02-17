<?php

namespace Social\Commands;

use Social\Entity\Logs;
use Social\Entity\Relationships;
use Social\Entity\User;
use Social\EntityBase;
use Social\Social;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * CLI command to install the mini social.
 */
class Install extends Command {

  /**
   * @var EntityBase[]
   */
  protected $entities;

  /**
   * {@inheritdoc}
   */
  public function __construct($name = null) {
    parent::__construct($name);

    $this->entities = [
      'user' => new User(),
      'logs' => new Logs(),
      'relationships' => new Relationships(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('social:install')
      ->setDescription('Install Mini social')
      ->setHelp('Set up the mini social stuff');
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {

    $io = new SymfonyStyle($input, $output);

    $this->setUpDb($io);
    $this->migrateData($io);
  }

  /**
   * @param SymfonyStyle $io
   */
  protected function setUpDb(SymfonyStyle $io) {
    $db = Social::getDb();

    $io->success('Installing the DB.');
    $db->createDb();

    foreach ($this->entities as $name => $entity) {
      $entity->createTable();
      $io->success('The ' . $name . ' table exists');
    }

    $io->success('All the entities exists. Migrating data.');
  }

  /**
   * @param SymfonyStyle $io
   */
  protected function migrateData(SymfonyStyle $io) {
  }

}
