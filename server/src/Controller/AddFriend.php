<?php

namespace Social\Controller;

use Social\ControllerBase;
use Social\Entity\User;

class AddFriend extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function response() {
    $data = json_decode($this->request->getContent());

    if (empty($data->friend_id)) {
      $this->badRequest('The friend_id is missing in the request.');
    }

    $current_user = $this->user;

    if ($data->friend_id == $current_user['id']) {
      $this->badRequest('You cannot add your self');
    }

    if (in_array($data->friend_id, $current_user['friends'])) {
      $this->badRequest('The friend you are trying to add already exists in your friend list.');
    }

    $user = new User();

    if (!$user->load($data->friend_id)) {
      $this->badRequest('There is no friend with the ID ' . $data->friend_id);
    }

    if (count($current_user['friends']) >= 5) {
      // Not enough room for more friends. Remove the first one.
      $removed_friend = $current_user['friends'][0];
      unset($current_user['friends'][0]);

      // Load the friend that was removed and un-friend him with the current
      // user.
      $removed_friend_object = $user->load($removed_friend);
      $key = array_search($current_user['id'], $removed_friend_object['friends']);
      unset($removed_friend_object['friends'][$key]);
      $user->update($removed_friend_object);
    }

    $current_user['friends'][] = $data->friend_id;
    $user->update($current_user);
  }

}
