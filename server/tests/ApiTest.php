<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Social\Controller\Login;
use Social\Entity\User;
use Social\Social;

class ApiTest extends TestCase {

  /**
   * @var \Social\RethinkDB
   */
  protected $db;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Run the migration part.
    shell_exec ( 'php console.php social:install --migrate_only true');

    $this->db = Social::getDb();
  }

  /**
   * Loading rick from the DB.
   */
  protected function loadRick() {
    $user = new User();
    $table = $user->getTable();
    $results = $table->filter(['email' => 'rick@sanchez.io'])->run($this->db->getConnection());

    return $results->toArray()[0]->getArrayCopy();
  }

  /**
   * Testing the api.
   *
   * @throws \Exception
   */
  public function testLogin() {
    $login = new Login();

    $result = json_decode($login->responseWrapper()->getContent());
    $this->assertEquals(['error' => 'username and password are missing'], (array) $result);

    $login->setPayload((object) ['username' => 'test', 'password' => 'test']);

    $result = json_decode($login->responseWrapper()->getContent());
    $this->assertEquals(['error' => 'No username was found. Try again later.'], (array) $result);

    $login->setPayload((object) ['username' => 'rick', 'password' => 'rick']);

    $result = json_decode($login->responseWrapper()->getContent());
    $this->assertEquals($this->loadRick()['id'], $result->id);
  }

}
