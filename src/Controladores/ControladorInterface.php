<?php

namespace Raiz\Controladores;

interface ControladorInterface
{
  /** @return mixed[] */
  public static function listar(): array;
  /** @return mixed[] */
  public static function buscarPorId(string $id): ?array;

  /**
   * @param mixed[] $parametros
   * @return mixed[]
   */
  public static function crear(array $parametros): array;
  /**
   * @param mixed[] $parametros
   * @return mixed[]
   */
  public static function actualizar(array $parametros): array;
  public static function borrar(string $id): void;
}
