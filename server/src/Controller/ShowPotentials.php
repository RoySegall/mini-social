<?php

namespace Social\Controller;

use r\ValuedQuery\RVar;
use Social\ControllerBase;
use Social\Entity\User;

class ShowPotentials extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function response() {
    $user = new User();
    $current_user = $this->user;
    $query = $user->getTable();

    $ids = $this->user['friends'];
    $row = function(RVar $doc) use ($ids) {
      return \r\expr($ids)->contains($doc->getField('id'))->not();
    };

    $query = $query->filter($row);

    return array_filter($user->processTableToArray($query), function($item) use($current_user) {
      if ($item['id'] == $current_user['id']) {
        return false;
      }

      // Keep a constant for the 5 days in seconds.
      $five_days = 5 * 86400;

      // Check of the birth date of the item is between the user birthday and
      // 5 days ago.
      $five_days_ago =
        ($item['birth_day_rank'] >= ($current_user['birth_day_rank'] - $five_days)) && $item['birth_day_rank'] <= $current_user['birth_day_rank'];

      // Check of the birth date of the item is between the user birthday and
      // 5 days ahead.
      $five_days_ahead =
        ($item['birth_day_rank'] <= ($current_user['birth_day_rank'] + $five_days)) && $item['birth_day_rank'] >= $current_user['birth_day_rank'];

      return ($five_days_ago || $five_days_ahead) && self::sharedHobbies($current_user['hobbies'], $item['hobbies']);
    });
  }

  /**
   * Check if the user we iterate over has at least one shared hobby.
   *
   * @param $main_user_hobbies
   *   The main user.
   * @param $other_user_hobbies
   *   The user we iterate over.
   *
   * @return bool
   */
  static protected function sharedHobbies($main_user_hobbies, $other_user_hobbies) {
    foreach ($main_user_hobbies as $hobby) {
      if (in_array($hobby, $other_user_hobbies)) {
        return true;
      }
    }
    return false;
  }

}
