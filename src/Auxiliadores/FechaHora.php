<?php

namespace Raiz\Auxiliadores;

use Brick\DateTime\LocalDateTime;
use Brick\DateTime\TimeZoneRegion;

class FechaHora
{
  public static function deserializar(?string $fecha): LocalDateTime
  {
    return is_null(value: $fecha)
      ? LocalDateTime::now(timeZone: TimeZoneRegion::utc())
      : LocalDateTime::parse(text: $fecha);
  }

  public static function deserializarOpcional(?string $fecha): ?LocalDateTime
  {
    return is_null(value: $fecha) ? null : LocalDateTime::parse(text: $fecha);
  }

  /**
   * El solo hecho de castear la variable al tipo fecha
   * es suficiente para transformar el dato a un string válido.
   *
   * Es equivalente a `(string) $fecha`
   */
  public static function serializar(?LocalDateTime $fecha): ?string
  {
    return $fecha;
  }
}
