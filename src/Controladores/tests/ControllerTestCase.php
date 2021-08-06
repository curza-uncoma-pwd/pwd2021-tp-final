<?php declare(strict_types=1);

namespace Raiz\Controladores\tests;

use JsonException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Raiz\Bd\Auxiliadores\Rehidratador;
use Raiz\Builder;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Stream;
use UnexpectedValueException;

class ControllerTestCase extends TestCase
{
  protected App $app;

  public static function setUpBeforeClass(): void
  {
    Rehidratador::ejecutar();
  }

  /**
   * Bootstrap app.
   *
   * @throws UnexpectedValueException
   */
  protected function setUp(): void
  {
    $this->app = Builder::buildApp();
  }

  /**
   * Create a server request.
   *
   * @param string $method The HTTP method
   * @param string|UriInterface $uri The URI
   * @param mixed[] $params The server parameters
   *
   * @return ServerRequestInterface
   */
  protected function createRequest(
    string $method,
    string|UriInterface $uri,
    array $params = [],
  ): ServerRequestInterface {
    return (new ServerRequestFactory())->createServerRequest(
      method: $method,
      uri: $uri,
      serverParams: $params,
    );
  }

  /**
   * Create a JSON request.
   *
   * @param string $method The HTTP method
   * @param string|UriInterface $uri The URI
   * @param mixed[]|null $data The json data
   *
   * @return ServerRequestInterface
   */
  protected function createJsonRequest(
    string $method,
    string|UriInterface $uri,
    array $data = null,
  ): ServerRequestInterface {
    $request = $this->createRequest(method: $method, uri: $uri);

    if ($data !== null) {
      $jsonStream = new Stream(
        stream: fopen(filename: 'php://temp', mode: 'w'),
      );

      $jsonStream->write(
        string: json_encode($data, flags: JSON_THROW_ON_ERROR, depth: 512),
      );
      $jsonStream->rewind();

      $request = $request->withBody(body: $jsonStream);
    }

    return $request->withHeader(
      name: 'Content-Type',
      value: 'application/json',
    );
  }

  /**
   * Verify that the given array is an exact match for the JSON returned.
   *
   * @param mixed[] $expected The expected array
   * @param ResponseInterface $response The response
   *
   * @throws JsonException
   */
  protected function assertJsonData(
    array $expected,
    ResponseInterface $response,
  ): void {
    $this->assertSame(
      expected: $expected,
      actual: $this->getJsonResponseData(response: $response),
    );
  }

  /**
   * Verify that the given array is an exact match for the JSON returned.
   *
   * @param ResponseInterface $response The response
   * @return string|mixed[]|int|null
   * @throws JsonException
   */
  protected function getJsonResponseData(
    ResponseInterface $response,
  ): string|array|int|null {
    $actual = (string) $response->getBody();

    return json_decode(
      json: $actual,
      associative: true,
      depth: 512,
      flags: JSON_THROW_ON_ERROR,
    );
  }
}
