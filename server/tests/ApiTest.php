<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Social\Controller\AllFriends;
use Social\Controller\Index;
use Social\Controller\Login;
use Social\Controller\ShowBirthdates;
use Social\Controller\ShowPotentials;
use Social\Controller\ShowUpcomingBirthdates;
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

  /**
   * Testing the birthdays controller.
   */
  public function testBirthdays() {
    $birthdays = new ShowBirthdates();
    $this->assertResponse([], $birthdays->setUid($this->loadRick()['id']), function($result) {

      $result = (array)$result;

      $this->assertCount(3, (array)$result);

      $friends = array_map(function($document) {
        return $document->username;
      }, $result);

      $this->assertTrue(in_array('admin', $friends));
      $this->assertTrue(in_array('jerry', $friends));
      $this->assertTrue(in_array('john', $friends));
    });
  }

  /**
   * Making sure we got the potentials friends.
   */
  public function testPotentialsFriends() {
    $potenials = new ShowPotentials();

    $this->assertResponse([], $potenials->setUid($this->loadRick()['id']), function($result) {

      $result = (array)$result;

      $this->assertCount(4, (array)$result);

      $friends = array_map(function($document) {
        return $document->username;
      }, $result);

      $this->assertTrue(in_array('ivanka', $friends));
      $this->assertTrue(in_array('malania', $friends));
      $this->assertTrue(in_array('tim', $friends));
      $this->assertTrue(in_array('steve', $friends));
    });
  }

  /**
   * Making sure we got the potentials friends.
   */
  public function testUpComingBirthdates() {
    $birthdates = new ShowUpcomingBirthdates();

    $this->assertResponse([], $birthdates->setUid($this->loadRick()['id']), function($result) {
      $result = (array)$result;

      $this->assertCount(14, (array)$result);

      $friends = array_map(function($document) {
        return $document->username;
      }, $result);


      $this->assertTrue(in_array('alice', $friends));
      $this->assertTrue(in_array('malania', $friends));
      $this->assertTrue(in_array('mr.misix', $friends));
      $this->assertTrue(in_array('admin', $friends));
      $this->assertTrue(in_array('beth', $friends));
      $this->assertTrue(in_array('sting', $friends));
      $this->assertTrue(in_array('morty', $friends));
      $this->assertTrue(in_array('jarrad', $friends));
      $this->assertTrue(in_array('summer', $friends));
      $this->assertTrue(in_array('steve', $friends));
      $this->assertTrue(in_array('tim', $friends));
      $this->assertTrue(in_array('ivanka', $friends));
      $this->assertTrue(in_array('jerry', $friends));
      $this->assertTrue(in_array('john', $friends));
    });
  }

  /**
   * Making sure the user object is return when accessing the index route.
   */
  public function testIndex() {
    $index = new Index();
    $this->assertResponse($this->loadRick(), $index->setUid($this->loadRick()['id']));
  }

}
