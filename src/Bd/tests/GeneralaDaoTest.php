<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Raiz\Bd\Auxiliadores\Rehidratador;
use Raiz\Bd\GeneralaDao;
use Raiz\Bd\JugadorDao;
use Raiz\Modelos\Juegos\Generala;

final class GeneralaDaoTest extends TestCase
{
  private static string $id;

  public static function setUpBeforeClass(): void
  {
    Rehidratador::ejecutar();
  }

  /** @test */
  public function lista_todos_los_juegos(): void
  {
    $juegos = GeneralaDao::listar();

    $this->assertIsArray(actual: $juegos);
    $this->assertGreaterThan(expected: 0, actual: sizeof(value: $juegos));
    $this->assertContainsOnlyInstancesOf(
      className: Generala::class,
      haystack: $juegos,
    );

    // TODO: verificar los jugadores de cada partida
  }

  /** @test */
  public function persiste_un_nuevo_juego(): void
  {
    $this->expectNotToPerformAssertions();

    $jugadores = JugadorDao::listar();

    $instancia = new Generala(
      jugadores: [$jugadores[0], $jugadores[1]],
      rondas: 2,
    );

    self::$id = $instancia->id();

    GeneralaDao::persistir(instancia: $instancia);
  }

  /** @test */
  public function busca_por_id(): void
  {
    $instancia = GeneralaDao::buscarPorId(id: self::$id);

    $this->assertInstanceOf(expected: Generala::class, actual: $instancia);
    // TODO: verificar los jugadores de cada partida
  }

  /** @test */
  public function actualiza_juego(): void
  {
    /** @var Generala */
    $antes = GeneralaDao::buscarPorId(id: self::$id);

    $antes->iniciar();
    $antes->realizarRonda();

    GeneralaDao::actualizar(instancia: $antes);

    /** @var Generala */
    $despues = GeneralaDao::buscarPorId(id: self::$id);

    $this->assertEquals(expected: $antes->estado(), actual: $despues->estado());
  }

  /** @test */
  public function borra_juego(): void
  {
    GeneralaDao::borrar(id: self::$id);

    $debeSerNulo = GeneralaDao::buscarPorId(id: self::$id);

    $this->assertNull(actual: $debeSerNulo);
    // TODO: verificar que los jugadores de la partida también se borraron
  }
}
