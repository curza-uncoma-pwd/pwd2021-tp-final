<?php

namespace Raiz\Rutas;

use Psr\Http\Message\ResponseInterface as Response;

class Utileria
{
  public static function responderConJson(
    Response $respuesta,
    mixed $datos,
  ): Response {
    $respuesta
      ->getBody()
      ->write(
        string: json_encode(
          value: $datos,
          flags: JSON_THROW_ON_ERROR,
          depth: 512,
        ),
      );

    return $respuesta->withHeader(
      name: 'Content-Type',
      value: 'application/json',
    );
  }
}
