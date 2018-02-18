<?php

namespace Social\Controller;

use Social\ControllerBase;

class Index extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function response() {
    return $this->user;
  }

}
