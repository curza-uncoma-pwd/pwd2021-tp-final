<?php declare(strict_types=1);

use Raiz\Bd\JugadorDao;
use Raiz\Controladores\tests\ControllerTestCase;
use Raiz\Modelos\Juegos\Generala;
use Raiz\Modelos\Jugador;

final class GeneralaControladorTest extends ControllerTestCase
{
  private static string $id;

  /** @test */
  public function lista_todas_las_partidas_de_generalas(): void
  {
    $request = $this->createRequest(method: 'GET', uri: '/api/generalas');

    $response = $this->app->handle(request: $request);

    $this->assertSame(expected: 200, actual: $response->getStatusCode());
    $data = $this->getJsonResponseData(response: $response);

    $this->assertEquals(expected: 3, actual: sizeof(value: $data));
  }

  /** @test */
  public function crea_nueva_partida_de_generala(): void
  {
    $jugadores = JugadorDao::listar();
    $ids = array_map(
      array: [$jugadores[0], $jugadores[1]],
      callback: fn(Jugador $jugador): string => $jugador->id(),
    );
    $request = $this->createJsonRequest(
      method: 'POST',
      uri: '/api/generalas',
      data: ['jugadorIds' => $ids, 'rondas' => 2],
    );

    $response = $this->app->handle(request: $request);

    $this->assertSame(expected: 200, actual: $response->getStatusCode());
    $data = $this->getJsonResponseData(response: $response);

    self::$id = $data['id'];

    $this->assertEquals(expected: 2, actual: $data['rondas']);
    $this->assertIsString(actual: $data['id']);
  }

  /** @test */
  public function inicia_la_partida_correctamente(): void
  {
    $id = self::$id;
    $request = $this->createRequest(
      method: 'PUT',
      uri: "/api/generalas/$id/iniciar",
    );
    $response = $this->app->handle(request: $request);

    $this->assertSame(expected: 200, actual: $response->getStatusCode());
    $data = $this->getJsonResponseData(response: $response);

    $this->assertEquals(
      expected: Generala::EN_PROGRESO,
      actual: $data['estado'],
    );
    $this->assertEquals(expected: 0, actual: $data['rondaActual']);
  }

  /** @test */
  public function pausa_la_partida_correctamente(): void
  {
    $id = self::$id;
    $request = $this->createRequest(
      method: 'PUT',
      uri: "/api/generalas/$id/pausar",
    );
    $response = $this->app->handle(request: $request);

    $this->assertSame(expected: 200, actual: $response->getStatusCode());
    $data = $this->getJsonResponseData(response: $response);

    $this->assertEquals(expected: Generala::EN_PAUSA, actual: $data['estado']);
    $this->assertEquals(expected: 0, actual: $data['rondaActual']);
  }

  /** @test */
  public function reanuda_la_partida_correctamente(): void
  {
    $id = self::$id;
    $request = $this->createRequest(
      method: 'PUT',
      uri: "/api/generalas/$id/reanudar",
    );
    $response = $this->app->handle(request: $request);

    $this->assertSame(expected: 200, actual: $response->getStatusCode());
    $data = $this->getJsonResponseData(response: $response);

    $this->assertEquals(
      expected: Generala::EN_PROGRESO,
      actual: $data['estado'],
    );
    $this->assertEquals(expected: 0, actual: $data['rondaActual']);
  }

  /** @test */
  public function realiza_una_ronda_correctamente(): void
  {
    $id = self::$id;
    $request = $this->createRequest(
      method: 'PUT',
      uri: "/api/generalas/$id/realizarRonda",
    );
    $response = $this->app->handle(request: $request);

    $this->assertSame(expected: 200, actual: $response->getStatusCode());
    $data = $this->getJsonResponseData(response: $response);
    $generalaDatos = $data['generala'];
    $resultado = $data['resultado'];

    $this->assertEquals(
      expected: Generala::EN_PROGRESO,
      actual: $generalaDatos['estado'],
    );
    $this->assertEquals(expected: 1, actual: $generalaDatos['rondaActual']);
    $this->assertEquals(expected: 1, actual: $resultado['ronda']);

    $this->assertIsArray(actual: $resultado['jugadores']);
    $this->assertEquals(expected: 2, actual: sizeof($resultado['jugadores']));
  }

  /** @test */
  public function completa_la_partida_correctamente(): void
  {
    $id = self::$id;
    $request = $this->createRequest(
      method: 'PUT',
      uri: "/api/generalas/$id/realizarRonda",
    );
    $response = $this->app->handle(request: $request);

    $this->assertSame(expected: 200, actual: $response->getStatusCode());
    $data = $this->getJsonResponseData(response: $response);
    $generalaDatos = $data['generala'];
    $resultado = $data['resultado'];

    $this->assertEquals(
      expected: Generala::COMPLETADO,
      actual: $generalaDatos['estado'],
    );
    $this->assertEquals(expected: 2, actual: $generalaDatos['rondaActual']);
    $this->assertEquals(expected: 2, actual: $resultado['ronda']);
  }

  /** @test */
  public function resetea_la_partida_corectamente(): void
  {
    $id = self::$id;
    $request = $this->createRequest(
      method: 'PUT',
      uri: "/api/generalas/$id/resetear",
    );
    $response = $this->app->handle(request: $request);

    $this->assertSame(expected: 200, actual: $response->getStatusCode());
    $data = $this->getJsonResponseData(response: $response);

    $this->assertEquals(
      expected: Generala::SIN_INICIAR,
      actual: $data['estado'],
    );
    $this->assertEquals(expected: 0, actual: $data['rondaActual']);
  }
}
