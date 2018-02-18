<?php

namespace Social\Entity;

use Social\EntityBase;

class User extends EntityBase {

  protected $table = 'user';

  /**
   * Hash the password.
   *
   * @param string $password
   *   The original password.
   *
   * @return string
   *   The hashed password.
   */
  public static function hashPassword($password) {
    return hash('sha512', $password);
  }

}
