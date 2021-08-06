<?php

namespace Raiz;

use Middlewares\TrailingSlash;
use Raiz\Rutas\GlobalRutas;
use Slim\Factory\AppFactory;
use Raiz\Rutas\JsonRespuestaMiddleware;
use Raiz\Rutas\JugadorRutas;
use Slim\App;

class Builder
{
  static function buildApp(): App
  {
    $app = AppFactory::create();

    $app->add(middleware: new JsonRespuestaMiddleware());
    $app->add(middleware: new TrailingSlash());

    GlobalRutas::configurar(app: $app);
    JugadorRutas::configurar(app: $app);

    return $app;
  }
}
