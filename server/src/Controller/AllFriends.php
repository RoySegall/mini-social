<?php

namespace Social\Controller;

use Social\ControllerBase;
use Social\Entity\User;

class AllFriends extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function response() {
    $user = new User();

    return $user->loadMultiple($this->user['friends']);
  }

  /**
   * Get the friends of friends.
   *
   * @param User $user
   *   The user entity object.
   *
   * @param $friends_ids
   *   List of friends for the given user.
   *
   * @return array
   *   List of friends of friends.
   */
  protected function getFriendsOfFriends(User $user, $friends_ids) {
    $friends = [];
    $friends_of_friends = array_map(function ($friend) use ($user, &$friends) {

      if (in_array($friend, array_keys($friends))) {
        // Already loaded this one. Return it.s
        return $friends[$friend];
      }

      // Get the user object.
      $friend_object = $user->load($friend);

      // Get the list of friends, save it for later(simple caching) and return
      // the list of friends.
      $friends[$friend] = $friend_object['friends'];

      return $friend_object['friends'];
    }, $friends_ids);

    foreach ($friends_of_friends as $friends) {
      $friends_ids = array_unique(array_merge($friends_ids, $friends));
    }

    return $friends_ids;
  }

}
