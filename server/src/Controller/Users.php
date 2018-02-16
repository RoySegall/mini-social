<?php

namespace Social\Controller;

use Social\ControllerBase;
use Social\Entity\User;

class Users extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function response() {
    $user = new User();
    return $user->loadMultiple();
  }

}
