<?php

namespace Raiz\Rutas;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

final class JsonRespuestaMiddleware implements MiddlewareInterface
{
  public function process(Request $request, RequestHandler $handler): Response
  {
    $contentType = $request->getHeaderLine(name: 'Content-Type');

    if (strstr(haystack: $contentType, needle: 'application/json')) {
      $contents = json_decode(
        json: $request->getBody()->getContents(),
        associative: true,
        flags: JSON_THROW_ON_ERROR,
      );
      $request = $request->withParsedBody(data: $contents);
    }

    return $handler->handle(request: $request);
  }
}
