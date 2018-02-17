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
   * Keep tracking of the user object and the entity.
   *
   * @var array
   */
  protected $usersMapping = [];

  /**
   * The IO object.
   *
   * @var SymfonyStyle
   */
  protected $io;

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
    $this->io = new SymfonyStyle($input, $output);

    $this->setUpDb();
    $this->migrateData();
  }

  /**
   * Setting up the DB.
   */
  protected function setUpDb() {
    $db = Social::getDb();

    $this->io->success('Installing the DB.');
    $db->createDb();

    foreach ($this->entities as $name => $entity) {
      $entity->createTable();
      $this->io->success('The ' . $name . ' table exists');
    }

    $this->io->success('All the entities exists. Migrating data.');
  }

  /**
   * Import data to the DB.
   */
  protected function migrateData() {
    $this->io->success('Truncating the tables. Just to make sure.');

    foreach ($this->entities as $entity) {
      $entity->deleteMultiple();
    }

    $this->io->success('Starting to import the entities.');

    $migrations = [
      'user' => 'userImport',
      'relationships' => 'relationshipImport',
    ];

    $assets = Social::parseYaml(file_get_contents('migration.yml'));

    foreach ($migrations as $entity => $callback) {
      $this->{$callback}($this->entities[$entity], $assets[$entity]);
    }
  }

  /**
   * Import users.
   *
   * @param \Social\Entity\User $user
   *   A user entity object.
   * @param array $users
   *   List of users to import.
   */
  protected function userImport(User $user, array $users) {

    $imported_users = [];

    foreach ($users as $key => $_user) {
      $result = $user->save($_user);
      $this->usersMapping[$key] = ['id' => $result['id'], 'name' => $_user['username']];
      $imported_users[] = $_user['username'];
    }

    $this->io->success('The users ' . implode(', ', $imported_users) . ' has created in the DB.');
  }

  /**
   * Import relationships of users.
   *
   * @param \Social\Entity\Relationships $relationship
   *   The relationship entity object.
   *
   * @param array $relationships
   *   List of relationships.
   */
  protected function relationshipImport(Relationships $relationship, array $relationships) {

    $created_relationships = [];

    foreach ($relationships as $_relationship) {
      $mapped_user = $this->usersMapping[$_relationship['owner']];

      $relationship->save([
        'user' => $mapped_user['id'],
        'friends' => array_map(function($item) {
          return $this->usersMapping[$item]['id'];
        }, $_relationship['friends']),
      ]);

      $created_relationships[] = $mapped_user['name'];
    }

    $this->io->success('Relationship for users ' . implode(', ', $created_relationships) . ' has created in the DB.');
  }

}
