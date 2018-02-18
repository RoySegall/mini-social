<?php

namespace Social\Controller;

use Social\ControllerBase;
use Social\Entity\User;
use Social\Social;

class ShowUpcomingBirthdates extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function response() {
    $user = new User();
    $current_user = $this->user;
    $users = $user->loadMultiple();

    return array_filter($users, function($user) use($current_user) {

      if ($user['id'] == $current_user['id']) {
        return false;
      }

      $ranking = $user['birth_day_rank'];

      return $ranking <= Social::calculateDayRank(12, 31) && $ranking > Social::calculateDayRank(date('m'), date('d'));
    });
  }

}
