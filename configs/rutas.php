<?php

require_once __DIR__ . '/../env.php';

use Raiz\Builder;

$app = Builder::buildApp();

$routes = $app->getRouteCollector()->getRoutes();
$list = [];

print_r(value: "Listado de rutas disponibles:\n");
print_r(value: "==============================================\n");
print_r(value: "= Verbo HTTP | Ruta relativa =================\n");
print_r(value: "==============================================\n");

foreach ($routes as $route) {
  print_r(
    value: str_pad(
      string: $route->getMethods()[0],
      length: 15,
      pad_type: STR_PAD_BOTH,
    ) .
      $route->getPattern() .
      "\n",
  );
}

print_r(value: "==============================================\n");
print_r(value: "= Verbo HTTP | Ruta relativa =================\n");

print_r(value: "\n");
