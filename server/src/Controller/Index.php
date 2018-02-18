<?php

namespace Social\Controller;

use Social\ControllerBase;

class Index extends ControllerBase {

  public function access() {
    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function response() {
    return [];
  }

}
