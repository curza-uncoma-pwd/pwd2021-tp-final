<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Raiz\Modelos\Cubilete;
use Raiz\Modelos\Juegos\Generala;
use Raiz\Modelos\Juegos\JuegoAbstracto;
use Raiz\Modelos\ModeloBase;
use Raiz\Utilidades\Logger;

final class CubileteTest extends TestCase
{
  /** @test */
  public function el_proceso_normal_funciona_correctamente(): void
  {
    $juego = new Generala(rondas: 3, jugadores: []);
    $cubilete = new Cubilete(juego: $juego);

    $cubilete->lanzarDados();

    // Esto se llama desestructurar arreglo:
    // https://stitcher.io/blog/array-destructuring-with-list-in-php
    [$valores, $cantidades] = $cubilete->resultado();

    Logger::info(mensaje: '');
    Logger::info(mensaje: 'Resultado:');

    for ($i = 0; $i < $juego->dadoValorMin(); $i++) {
      $this->assertNull(actual: $valores[$i]);
      $this->assertNull(actual: $cantidades[$i]);
    }

    for ($i = $juego->dadoValorMin(); $i <= $juego->dadoValorMax(); $i++) {
      Logger::info(mensaje: "$i * {$valores[$i]} = {$cantidades[$i]}");

      $this->assertEquals(expected: $valores[$i] * $i, actual: $cantidades[$i]);
    }
  }

  /** @test */
  public function el_arreglo_de_dados_del_tres_al_cinco_es_correcto(): void
  {
    $juego = new JuegoTest(min: 3, max: 5);
    $cubilete = new Cubilete(juego: $juego);

    $cubilete->lanzarDados();

    // Esto se llama desestructurar arreglo:
    // https://stitcher.io/blog/array-destructuring-with-list-in-php
    [$valores, $cantidades] = $cubilete->resultado();

    Logger::info(mensaje: '');
    Logger::info(mensaje: 'Resultado:');

    for ($i = 0; $i < $juego->dadoValorMin(); $i++) {
      $this->assertNull(actual: $valores[$i]);
      $this->assertNull(actual: $cantidades[$i]);
    }

    for ($i = $juego->dadoValorMin(); $i <= $juego->dadoValorMax(); $i++) {
      Logger::info(mensaje: "$i * {$valores[$i]} = {$cantidades[$i]}");

      $this->assertEquals(expected: $valores[$i] * $i, actual: $cantidades[$i]);
    }
  }

  /** @test */
  public function el_arreglo_de_dados_del_cero_al_cuatro_es_correcto(): void
  {
    $juego = new JuegoTest(min: 0, max: 4);
    $cubilete = new Cubilete(juego: $juego);

    $cubilete->lanzarDados();

    // Esto se llama desestructurar arreglo:
    // https://stitcher.io/blog/array-destructuring-with-list-in-php
    [$valores, $cantidades] = $cubilete->resultado();

    Logger::info(mensaje: '');
    Logger::info(mensaje: 'Resultado:');

    for ($i = 0; $i < $juego->dadoValorMin(); $i++) {
      $this->assertNull(actual: $valores[$i]);
      $this->assertNull(actual: $cantidades[$i]);
    }

    for ($i = $juego->dadoValorMin(); $i <= $juego->dadoValorMax(); $i++) {
      Logger::info(mensaje: "$i * {$valores[$i]} = {$cantidades[$i]}");

      $this->assertEquals(expected: $valores[$i] * $i, actual: $cantidades[$i]);
    }
  }
}

class JuegoTest extends JuegoAbstracto
{
  public function __construct(int $min, int $max)
  {
    parent::__construct(
      id: null,
      estado: null,
      inicio: null,
      fin: null,
      cantidadDeDados: 5,
      dadoValorMin: $min,
      dadoValorMax: $max,
      jugadores: [],
    );
  }
  public function verResultado(): void
  {
  }
  protected function procesarRonda(): array
  {
    return [];
  }
  protected function verificarSiSeCompleto(): bool
  {
    return true;
  }

  public static function deserializar(array $datos): ModeloBase
  {
    return new self(...$datos);
  }
}
