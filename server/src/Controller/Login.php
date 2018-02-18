<?php

namespace Social\Controller;

use Social\ControllerBase;
use Social\Entity\User;
use Social\EntityBase;

class Login extends ControllerBase {

  protected function access() {
    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function response() {
    $data = json_decode($this->request->getContent());

    if (empty($data->username) || empty($data->password)) {
      $this->badRequest('username and password are missing');
    }

    $data->password = User::hashPassword($data->password);

    $user = new User();
    $results = $user->getTable()
      ->filter((array) $data)
      ->run($user->getConnection());

    if (!$results) {
      $this->badRequest('No username was found. Try again later.');
    }

    // we should create an access token but for the lack of time we won't do
    // that.
    // todo: create access token.
    return EntityBase::processCursor($results);
  }

}
