<?php

namespace Social;

use Symfony\Component\Yaml\Yaml;

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

  /**
   * Parse YAML files.
   *
   * @param $content
   *   The yml content.
   *
   * @return mixed
   *   The parsed object.s
   */
  public static function parseYaml($content) {
    return Yaml::parse($content);
  }

}
