<?php

namespace Social;

class Social {

  /**
   * Get the DB handler.
   *
   * @return \Social\RethinkDB
   */
  public static function getDb() {
    // todo: read from settings.php file.
    return new RethinkDB();
  }

}
