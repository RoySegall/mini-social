<?php

namespace Social;

use Social\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ControllerBase.
 *
 * Base class for all the routes.
 *
 * @package Social
 */
abstract class ControllerBase {

  /**
   * @var Request
   */
  protected $request;

  /**
   * @var array
   */
  protected $user;

  /**
   * ControllerBase constructor.
   */
  public function __construct() {
    $this->request = Request::createFromGlobals();
  }

  /**
   * Wrap the response method with access logic and other stuff.
   *
   * @return JsonResponse
   *   The response.
   */
  public function responseWrapper() {

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Headers: Authorization, access_token, access-token, Content-Type, permission');
    header('Access-Control-Allow-Methods: *');

//    if (!$this->access()) {
//      return $this->accessDenied();
//    }

    try {
      $content = $this->response();
      $code = 200;
    } catch (HttpException $e) {
      $content = ['error' => $e->getMessage()];
      $code = Response::HTTP_UNAUTHORIZED;
    }

    $response = new JsonResponse($content, $code);
    return $response;
  }

  /**
   * Making sure the user have or won't have access to the page.
   *
   * @return bool
   */
  protected function access() {
    // Only logged users have access to the routes.
    return $this->getCurrentUser();
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
   * Get the current user in the request.
   *
   * @return \Social\Entity\User|bool
   */
  protected function getCurrentUser() {
    $user = new User();

    if (!$this->user = $user->load($this->request->headers->get('uid'))) {
      return false;
    }

    return $this->user;
  }

  /**
   * Trow a bad request.
   *
   * @param $message
   */
  protected function badRequest($message) {
    throw new HttpException(Response::HTTP_BAD_REQUEST, $message);
  }

  /**
   * The response of the controller. Owns the business logic fo the controller.
   *
   * @return array
   *   The date of the response.
   */
  abstract function response();

}
