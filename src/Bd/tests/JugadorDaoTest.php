<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Raiz\Bd\Auxiliadores\Rehidratador;
use Raiz\Bd\JugadorDao;
use Raiz\Modelos\Jugador;

final class JugadorDaoTest extends TestCase
{
  private static string $id;

  public static function setUpBeforeClass(): void
  {
    Rehidratador::ejecutar();
  }

  /** @test */
  public function lista_todos_los_jugadores(): void
  {
    $jugadores = JugadorDao::listar();

    $this->assertIsArray(actual: $jugadores);
    $this->assertGreaterThan(expected: 0, actual: sizeof(value: $jugadores));
    $this->assertContainsOnlyInstancesOf(
      className: Jugador::class,
      haystack: $jugadores,
    );
  }

  /** @test */
  public function persiste_un_nuevo_jugador(): void
  {
    $this->expectNotToPerformAssertions();

    $instancia = new Jugador(nombre: 'Nuevo Jugador');

    self::$id = $instancia->id();

    JugadorDao::persistir(instancia: $instancia);
  }

  /** @test */
  public function busca_por_id(): void
  {
    $instancia = JugadorDao::buscarPorId(id: self::$id);

    $this->assertInstanceOf(expected: Jugador::class, actual: $instancia);
  }

  /** @test */
  public function actualiza_jugador(): void
  {
    /** @var Jugador */
    $antes = JugadorDao::buscarPorId(id: self::$id);

    $antes->setNombre(nombre: 'Cambio de nombre');

    JugadorDao::actualizar(instancia: $antes);

    /** @var Jugador */
    $despues = JugadorDao::buscarPorId(id: self::$id);

    $this->assertEquals(expected: $antes->nombre(), actual: $despues->nombre());
  }

  /** @test */
  public function borra_jugador(): void
  {
    JugadorDao::borrar(id: self::$id);

    $debeSerNulo = JugadorDao::buscarPorId(id: self::$id);

    $this->assertNull(actual: $debeSerNulo);
  }
}
