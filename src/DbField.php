<?php

namespace Social;

class DbField {

  /**
   * @return DbField
   */
  public static function init() {
    return new static();
  }

  protected $name;

  protected $type;

  public function name($field_name) {
    $this->name = $field_name;

    return $this;
  }

  public function type($type) {
    $this->type = $type;

    return $this;
  }

  public function getField() {
    return $this->name;
  }

}
