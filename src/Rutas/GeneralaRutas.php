<?php

namespace Raiz\Rutas;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Raiz\Controladores\GeneralaControlador;
use Slim\App;

final class GeneralaRutas implements RutasInterface
{
  public static function configurar(App $app): void
  {
    // API
    $app->get(
      pattern: '/api/generalas',
      callable: function (Request $peticion, Response $respuesta, $args) {
        return Utileria::responderConJson(
          respuesta: $respuesta,
          datos: GeneralaControlador::listar(),
        );
      },
    );

    $app->get(
      pattern: '/api/generalas/{id}',
      callable: function (Request $peticion, Response $respuesta, array $args) {
        $datos = GeneralaControlador::buscarPorId(id: $args['id']);

        if (is_null(value: $datos)) {
          $respuesta = $respuesta->withStatus(404);
        }

        return Utileria::responderConJson(respuesta: $respuesta, datos: $datos);
      },
    );

    $app->post(
      pattern: '/api/generalas',
      callable: function (Request $peticion, Response $respuesta, $args) {
        return Utileria::responderConJson(
          respuesta: $respuesta,
          datos: GeneralaControlador::crear(
            parametros: $peticion->getParsedBody(),
          ),
        );
      },
    );

    $app->put(
      pattern: '/api/generalas/{id}/iniciar',
      callable: function (Request $peticion, Response $respuesta, array $args) {
        return Utileria::responderConJson(
          respuesta: $respuesta,
          datos: GeneralaControlador::iniciar(id: $args['id']),
        );
      },
    );

    $app->put(
      pattern: '/api/generalas/{id}/pausar',
      callable: function (Request $peticion, Response $respuesta, array $args) {
        return Utileria::responderConJson(
          respuesta: $respuesta,
          datos: GeneralaControlador::pausar(id: $args['id']),
        );
      },
    );

    $app->put(
      pattern: '/api/generalas/{id}/reanudar',
      callable: function (Request $peticion, Response $respuesta, array $args) {
        return Utileria::responderConJson(
          respuesta: $respuesta,
          datos: GeneralaControlador::reanudar(id: $args['id']),
        );
      },
    );

    $app->put(
      pattern: '/api/generalas/{id}/resetear',
      callable: function (Request $peticion, Response $respuesta, array $args) {
        return Utileria::responderConJson(
          respuesta: $respuesta,
          datos: GeneralaControlador::resetear(id: $args['id']),
        );
      },
    );

    $app->put(
      pattern: '/api/generalas/{id}/realizarRonda',
      callable: function (Request $peticion, Response $respuesta, array $args) {
        return Utileria::responderConJson(
          respuesta: $respuesta,
          datos: GeneralaControlador::realizarRonda(id: $args['id']),
        );
      },
    );

    $app->delete(
      pattern: '/api/generalas/{id}',
      callable: function (Request $peticion, Response $respuesta, array $args) {
        GeneralaControlador::borrar(id: $args['id']);

        return $respuesta;
      },
    );
  }
}
