<?php

namespace Raiz\Rutas;

use Slim\App;

interface RutasInterface
{
  public static function configurar(App $app): void;
}
