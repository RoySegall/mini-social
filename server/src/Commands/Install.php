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
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('social:install')
      ->setDescription('Install Mini social')
      ->setHelp('Set up the mini social stuff')
      ->addOption('migrate_only', 'mo', InputOption::VALUE_OPTIONAL, 'Define if need only to remigrate content.', FALSE);
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->io = new SymfonyStyle($input, $output);

    if (!$input->getOption('migrate_only')) {
      $this->setUpDb();
    }

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
      $this->{$callback}($this->entities['user'], $assets[$entity]);
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
      // Process values from the migration file.
      $_user['password'] = User::hashPassword($_user['password']);

      if (strpos($_user['birthdate'], '/') === false) {
        $_user['birthdate'] = date("m/d/Y", strtotime($_user['birthdate']));
      }

      list($month, $day, $year) = explode('/', $_user['birthdate']);

      if ($year == date("Y")) {
        $year = $year - 27;
        $_user['birthdate'] = "{$month}/{$day}/{$year}";
      }

      $_user['birth_day_rank'] = Social::calculateDayRank($month, $day);
      $_user['birth_year'] = $year;

      // Saving the user.
      $result = $user->save($_user);

      // Saved data for later usage.
      $this->usersMapping[$key] = $result;
      $imported_users[] = $_user['username'];
    }

    $this->io->success('The users ' . implode(', ', $imported_users) . ' has created in the DB.');
  }

  /**
   * Import relationships of users.
   *
   * @param \Social\Entity\User $user
   *   The user entity object.
   *
   * @param array $relationships
   *   List of relationships.
   */
  protected function relationshipImport(User $user, array $relationships) {

    $created_relationships = [];

    // Updating the user with the relationships.
    foreach ($relationships as $relationship) {
      $mapped_user = $this->usersMapping[$relationship['owner']];

      $mapped_user['friends'] = array_map(function($item) {
        return $this->usersMapping[$item]['id'];
      }, $relationship['friends']);

      $user->update($mapped_user);

      $created_relationships[] = $mapped_user['username'];
    }

    $this->io->success('Relationship for users ' . implode(', ', $created_relationships) . ' has created in the DB.');
  }

}
