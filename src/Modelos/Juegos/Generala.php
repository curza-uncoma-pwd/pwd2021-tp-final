<?php

namespace Raiz\Modelos\Juegos;

use Raiz\Modelos\Cubilete;
use Raiz\Modelos\Juegos\Errores\JuegoError;
use Raiz\Modelos\Jugador;
use Raiz\Utilidades\Logger;

final class Generala extends JuegoAbstracto
{
  private int $rondas;

  /**
   * Si el estado es SIN_INICIAR: debe ser -1.
   * Si el estado es COMPLETADO: debe ser igual a la cantidad
   * de rondas.
   * Si es cualquier otro estado: debe ser un valor mayor a 0 y
   * menor a la cantidad de rondas.
   */
  private int $rondaActual;

  /** @param Array<Jugador> $jugadores */
  public function __construct(
    array $jugadores,
    int $rondas,
    ?string $id = null,
    ?string $estado = null,
    ?string $inicio = null,
    ?string $fin = null,
    int $rondaActual = -1,
  ) {
    parent::__construct(
      id: $id,
      estado: $estado,
      inicio: $inicio,
      fin: $fin,
      cantidadDeDados: 5,
      dadoValorMin: 1,
      dadoValorMax: 6,
      jugadores: $jugadores,
    );

    $this->rondas = $rondas;
    $this->rondaActual = $rondaActual;
  }

  public function serializar(): array
  {
    $datos = parent::serializar();

    $datos['tipo'] = self::class;
    $datos['rondas'] = $this->rondas;
    $datos['rondaActual'] = $this->rondaActual;

    return $datos;
  }

  public static function deserializar(array $datos): self
  {
    return new self(
      id: $datos['id'],
      estado: $datos['estado'],
      inicio: $datos['inicio'],
      fin: $datos['fin'],
      rondas: $datos['rondas'],
      rondaActual: $datos['rondaActual'],
      // NOTA: el mapeo transforma datos. Toma el
      // valor de entrada y devuelve otra cosa.
      jugadores: array_map(
        // NOTA: Función lambda
        callback: fn($jugador): Jugador => Jugador::deserializar(
          datos: $jugador,
        ),
        array: $datos['jugadores'],
      ),
    );
  }

  public function iniciar(): void
  {
    parent::iniciar();

    $this->rondaActual = 0;
  }

  public function resetear(): void
  {
    parent::resetear();

    $this->rondaActual = 0;
  }

  /** @return mixed[] */
  protected function procesarRonda(): array
  {
    $this->rondaActual++;

    $respuesta = [
      'ronda' => $this->rondaActual,
      'jugadores' => [],
    ];

    foreach ($this->jugadores() as $jugador) {
      $jugador->realizarTurno(cubilete: $this->cubilete());

      $resultado = [
        'jugador' => $jugador->nombre(),
        'resultado' => $this->calcularResultadoDeJugada(
          cubilete: $this->cubilete(),
        ),
      ];

      array_push($respuesta['jugadores'], $resultado);
    }

    return $respuesta;
  }

  protected function verificarSiSeCompleto(): bool
  {
    return $this->rondaActual === $this->rondas;
  }

  public function verResultado(): void
  {
    if ($this->estado() !== self::COMPLETADO) {
      throw new JuegoError(
        juego: $this,
        codigo: JuegoError::VER_RESULTADOS_SIN_HABER_COMPLETADO,
      );
    }

    Logger::info(
      mensaje: 'En esta generala no hay ganadores (faltó hacer 🙃).',
    );
  }

  /**
   * @return mixed[]
   */
  private function calcularResultadoDeJugada(Cubilete $cubilete): array
  {
    [$valores, $cantidades] = $cubilete->resultado();

    return [
      'as' => [$valores[1], $cantidades[1]],
      'dos' => [$valores[2], $cantidades[2]],
      'tres' => [$valores[3], $cantidades[3]],
      'cuatro' => [$valores[4], $cantidades[4]],
      'cinco' => [$valores[5], $cantidades[5]],
      'seis' => [$valores[6], $cantidades[6]],
      'escalera' => $this->esEscalera(valores: $valores),
      'full' => $this->esFullHouse(valores: $valores),
      'poker' => $this->esPoker(valores: $valores),
      'generala' => $this->esGenerala(valores: $valores),
    ];
  }

  /**
   * @param int[] $valores
   */
  private function esEscalera(array $valores): string
  {
    $escaleraDelUnoAlCinco =
      $valores[1] === 1 &&
      $valores[2] === 1 &&
      $valores[3] === 1 &&
      $valores[4] === 1 &&
      $valores[5] === 1;
    $escaleraDelDosAlSeis =
      $valores[2] === 1 &&
      $valores[3] === 1 &&
      $valores[4] === 1 &&
      $valores[5] === 1 &&
      $valores[6] === 1;
    $escaleraDelTresAlUno =
      $valores[3] === 1 &&
      $valores[4] === 1 &&
      $valores[5] === 1 &&
      $valores[6] === 1 &&
      $valores[1] === 1;

    return $escaleraDelUnoAlCinco ||
      $escaleraDelDosAlSeis ||
      $escaleraDelTresAlUno
      ? 'sí'
      : 'no';
  }

  /**
   * @param int[] $valores
   */
  private function esGenerala(array $valores): string
  {
    foreach ($valores as $valor) {
      if ($valor === 5) {
        return 'sí';
      }
    }

    return 'no';
  }

  /**
   * @param int[] $valores
   */
  private function esPoker(array $valores): string
  {
    foreach ($valores as $valor) {
      if ($valor === 4) {
        return 'sí';
      }
    }

    return 'no';
  }

  /**
   * @param int[] $valores
   */
  private function esFullHouse(array $valores): string
  {
    $hayDoble = false;
    $hayTripe = false;
    foreach ($valores as $valor) {
      if ($valor === 2) {
        $hayDoble = true;
      }
      if ($valor === 3) {
        $hayTripe = true;
      }
    }

    return $hayDoble && $hayTripe ? 'sí' : 'no';
  }
}
