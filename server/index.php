<?php

/**
 * @file
 * index.php file.
 */

require_once 'vendor/autoload.php';

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

// Set up the route collection.
$routes = new RouteCollection();

// Set the paths.
$paths = \Social\Social::parseYaml(file_get_contents('routes.yml'));
foreach ($paths as $handler => $namespace) {
  $routes->add($handler, new Route('/' . $handler, array(
      '_controller' => $namespace . '::responseWrapper')
  ));
}

// Some stuff.
$request = Request::createFromGlobals();
$matcher = new UrlMatcher($routes, new RequestContext());
$dispatcher = new EventDispatcher();

$dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));
$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$kernel = new HttpKernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);
$response = $kernel->handle($request);

// Fire up.
$response->send();

// Done.
$kernel->terminate($request, $response);

