<?php

namespace Social;

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class ControllerBase {

  public function responseWrapper() {
    return new JsonResponse($this->response());
  }

  /**
   * The response of the controller.
   *
   * @return mixed
   */
  abstract function response();

}