<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Social\Controller\AllFriends;
use Social\Controller\Login;
use Social\ControllerBase;
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
   * Asserting request easilly.
   *
   * @param array $response
   *   The array which will be assert against the request.
   * @param ControllerBase $request
   *   The controller to validate.
   * @param callable|NULL $assert
   *   Custom callback to validate.
   */
  protected function assertResponse(array $response, ControllerBase $request, callable $assert = NULL) {
    $result = json_decode($request->responseWrapper()->getContent());

    if ($assert) {
      $assert($result);
      return;
    }

    $this->assertEquals($response, (array) $result);
  }

  /**
   * Testing login API.
   *
   * @throws \Exception
   */
  public function testLogin() {
    $login = new Login();

    $this->assertResponse(['error' => 'username and password are missing'], $login);
    $this->assertResponse(['error' => 'No username was found. Try again later.'], $login->setPayload([
      'username' => 'test',
      'password' => 'test',
    ]));

    $this->assertResponse(['error' => 'No username was found. Try again later.'], $login->setPayload([
      'username' => 'test',
      'password' => 'test',
    ]));

    $this->assertResponse([], $login->setPayload(['username' => 'rick', 'password' => 'rick']), function($result) {
      $this->assertEquals($this->loadRick()['id'], $result->id);
    });
  }

  /**
   * Making sure we are getting the right friends.
   */
  public function testAllFriend() {
    $allFriends = new AllFriends();

    $this->assertResponse([], $allFriends->setUid($this->loadRick()['id']), function($result) {
      $this->assertCount(5, $result);

      $friends = array_map(function($document) {
        return $document->username;
      }, $result);

      $this->assertTrue(in_array('admin', $friends));
      $this->assertTrue(in_array('morty', $friends));
      $this->assertTrue(in_array('alice', $friends));
      $this->assertTrue(in_array('jerry', $friends));
      $this->assertTrue(in_array('john', $friends));
    });
  }

}
