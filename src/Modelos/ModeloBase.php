<?php

namespace Raiz\Modelos;

use EndyJasmi\Cuid;
use Error;
use Raiz\Auxiliadores\Serializador;

abstract class ModeloBase implements Serializador
{
  private string $id;

  public function __construct(?string $id)
  {
    $this->id = $id ? $id : Cuid::slug();
  }

  public function id(): string
  {
    return $this->id;
  }

  public function esIgual(ModeloBase $instancia): bool
  {
    return $this->id === $instancia->id();
  }

  /** @return mixed[] */
  public function serializar(): array
  {
    throw new Error(message: 'Serialización no implementada.');
  }

  /** @param mixed[] $datos */
  public static function deserializar(array $datos): ModeloBase
  {
    throw new Error(message: 'Deserialización no implementada.');
  }
}
