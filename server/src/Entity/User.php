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

  /**
   * Massage a single item to whatever we need - change date or add some values.
   *
   * @param $item
   *   The item to process.
   *
   * @return mixed
   */
  protected function massageElement($item) {
    $item['birthdate'] = date("m/d/Y" , $item['birthdate']);
    $item['time'] = date("m/d/Y" , $item['time']);
    return $item;
  }

}
