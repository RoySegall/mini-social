<?php

namespace Social\Controller;

use Social\ControllerBase;
use Social\Entity\Logs;
use Social\Entity\User;
use Social\EntityBase;
use Symfony\Component\Filesystem\Filesystem;

class Login extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  protected function access() {
    // Everyone should have access to the login page.
    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function response() {
    $data = $this->processPayload();

    if (empty($data->username) || empty($data->password)) {
      $this->badRequest('username and password are missing');
    }

    $data->password = User::hashPassword($data->password);

    $user = new User();
    $results = $user->getTable()
      ->filter((array) $data)
      ->run($user->getConnection());

    if (!$users = $results->toArray()) {
      // Writing to the log.
      $this->logFailedLogin($data->username);
      $this->badRequest('No username was found. Try again later.');
    }

    // we should create an access token but for the lack of time we won't do
    // that.
    // todo: create access token.
    return $users[0]->getArrayCopy();
  }

  /**
   * Log how many times the user failed with login from the same system.
   *
   * @param string $username
   *   The username.
   */
  protected function logFailedLogin($username) {
    $logs = new Logs();
    $document = [
      'username' => $username,
      'ip' => $this->request->server->get("REMOTE_ADDR"),
    ];

    $results = $logs
      ->getTable()
      ->filter($document)
      ->run($logs->getConnection());

    if (count($results->toArray()) >= 5) {
      // Write to the log.
      $data = [
        'ip' => $this->request->server->get("REMOTE_ADDR"),
        'date' => date('m/d/Y i:H'),
      ];
      $string = "Failed login occurred at {$data['date']} from the IP {$data['ip']}\n";

      $fs = new Filesystem();
      $fs->appendToFile('failed_logs.log', $string);
    }

    $logs->save($document);
  }

}
