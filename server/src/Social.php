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

  /**
   * Calculating how much time has passed since the the begining of the year.
   *
   * @param $month
   * @param $day
   *
   * @return int
   */
  public static function calculateDayRank($month, $day) {

    // Preparing the date. Since we cannot know with math how much time passed
    // since the date we need to track how much has many days in seconds has
    // passed.
    $months = [
      '01' => 31, '02' => 28, '03' => 31,
      '04' => 30, '05' => 31, '06' => 30,
      '07' => 31, '08' => 31, '09' => 30,
      '10' => 31, '11' => 30, '12' => 31];

    return ($month * 86400 * $months[$month]) + ($day * 86400);
  }

}
