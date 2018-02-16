<?php

namespace Social;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class ControllerBase {

  /**
   * @var Request
   */
  protected $request;

  /**
   * ControllerBase constructor.
   */
  public function __construct() {
    $this->request = new Request();
  }

  /**
   * Wrap the response method with access logic and other stuff.
   *
   * @return JsonResponse
   *   The response.
   */
  public function responseWrapper() {

    if (!$this->access()) {
      return $this->accessDenied();
    }
    return new JsonResponse($this->response());
  }

  /**
   * Making sure the user have or won't have access to the page.
   *
   * @return bool
   */
  protected function access() {
    return true;
  }

  /**
   * Return an access denied response.
   *
   * @return JsonResponse
   *   The response to display.
   */
  final protected function accessDenied() {
    return new JsonResponse(['error' => 'You do not have access to this page.'], Response::HTTP_FORBIDDEN);
  }

  /**
   * The response of the controller. Owns the business logic fo the controller.
   *
   * @return array
   *   The date of the response.
   */
  abstract function response();

}