<?php declare(strict_types=1);

use Raiz\Controladores\tests\ControllerTestCase;

final class JugadorControladorTest extends ControllerTestCase
{
  private static string $id;
  private static string $nombre = 'Nuevo jugador';

  /** @test */
  public function lista_jugadores_correctamente(): void
  {
    $request = $this->createRequest(method: 'GET', uri: '/api/jugadores');

    $response = $this->app->handle(request: $request);

    $this->assertSame(expected: 200, actual: $response->getStatusCode());
    $data = $this->getJsonResponseData(response: $response);

    $this->assertEquals(expected: 7, actual: sizeof(value: $data));
  }

  /** @test */
  public function crea_nuevo_jugador(): void
  {
    $nombre = self::$nombre;
    $request = $this->createJsonRequest(
      method: 'POST',
      uri: '/api/jugadores',
      data: ['nombre' => $nombre],
    );

    $response = $this->app->handle(request: $request);

    $this->assertSame(expected: 200, actual: $response->getStatusCode());
    $data = $this->getJsonResponseData(response: $response);

    self::$id = $data['id'];

    $this->assertEquals(expected: $nombre, actual: $data['nombre']);
    $this->assertIsString(actual: $data['id']);
  }

  /** @test */
  public function busca_por_id_correctamente(): void
  {
    $id = self::$id;
    $request = $this->createRequest(method: 'GET', uri: "/api/jugadores/$id");

    $response = $this->app->handle(request: $request);

    $this->assertSame(expected: 200, actual: $response->getStatusCode());
    $data = $this->getJsonResponseData(response: $response);

    $this->assertEquals(expected: self::$nombre, actual: $data['nombre']);
  }

  /** @test */
  public function actualiza_jugador(): void
  {
    $id = self::$id;
    $nombreNuevo = 'blablalb';
    $request = $this->createJsonRequest(
      method: 'PUT',
      uri: "/api/jugadores/$id",
      data: ['nombre' => $nombreNuevo],
    );
    $response = $this->app->handle(request: $request);

    $this->assertSame(expected: 200, actual: $response->getStatusCode());
    $data = $this->getJsonResponseData(response: $response);

    $this->assertEquals(expected: $nombreNuevo, actual: $data['nombre']);
  }

  /** @test */
  public function elimina_jugador(): void
  {
    $id = self::$id;
    $request = $this->createRequest(
      method: 'DELETE',
      uri: "/api/jugadores/$id",
    );
    $response = $this->app->handle(request: $request);

    $this->assertSame(expected: 200, actual: $response->getStatusCode());
    $request = $this->createRequest(method: 'GET', uri: "/api/jugadores/$id");

    $response = $this->app->handle(request: $request);

    $this->assertSame(expected: 404, actual: $response->getStatusCode());
  }
}
