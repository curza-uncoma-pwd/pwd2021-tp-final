<?php

require_once __DIR__ . '/../env.php';

use Raiz\Builder;

$app = Builder::buildApp();

$routes = $app->getRouteCollector()->getRoutes();
$list = [];

print_r(value: "\n");
print_r(value: "Listado de rutas disponibles:\n");
print_r(value: "==============================================\n");
print_r(value: "\n");

foreach ($routes as $route) {
  print_r(
    value: $route->getPattern() .
      ' ' .
      json_encode(
        value: $route->getMethods(),
        flags: JSON_THROW_ON_ERROR,
        depth: 512,
      ) .
      "\n",
  );
}
