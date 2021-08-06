<?php

namespace Raiz\Modelos\Juegos\Errores;

use Error;
use Raiz\Modelos\Juegos\Generala;

class GeneralaError extends Error
{
  public const FALTA_CONFIGURAR = 300;
  private const ERROR_DESCONOCIDO = -1;

  public function __construct(Generala $juego, int $codigo)
  {
    $id = $juego->id();

    switch ($codigo) {
      default:
        parent::__construct(
          message: "Acción {{$codigo}} desconocida.",
          code: self::ERROR_DESCONOCIDO,
        );
        break;
    }
  }
}
