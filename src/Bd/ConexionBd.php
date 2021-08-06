<?php

namespace Raiz\Bd;

use Closure;
use PDO;
use PdoDebugger;
use Raiz\Utilidades\Logger;

final class ConexionBd
{
  private static ?PDO $conexion = null;

  private function __construct()
  {
  }

  /**
   * Ejemplo de uso:
   * ```php
   * ConexionBd::escribir(
   *   sql: "INSERT INTO `jugadores` VALUES (/* lista de valores /)",
   *   // Opcional
   *   params: [],
   * )
   * ```
   *
   * @param string $sql Consulta SQL.
   * @param mixed[] $params Arreglo asociativo con los
   *  parámetros utilizados en la consulta.
   */
  public static function escribir(string $sql, array $params = []): void
  {
    $conexion = self::conectar();
    $consulta = $conexion->prepare(query: $sql);

    Logger::info(
      mensaje: PdoDebugger::show(raw_sql: $sql, parameters: $params),
    );

    $consulta->execute(params: $params);

    $consulta->closeCursor();
  }
  /**
   * Ejemplo de uso:
   * ```php
   * static::leer(
   *   sql: "SELECT * FROM jugadores",
   *   // Opcional
   *   params: [],
   *   transformador: function (PDOStatement $consulta) {
   *     return $consulta->fetch(PDO::FETCH_ASSOC);
   *   },
   * )
   * ```
   *
   * @param string $sql Consulta SQL.
   * @param Closure $transformador Es una función que se
   * utiliza para procesar los datos de la db.
   * @param mixed[] $params Arreglo asociativo con los
   *  parámetros utilizados en la consulta.
   */
  public static function leer(
    string $sql,
    Closure $transformador,
    array $params = [],
  ): mixed {
    $conexion = self::conectar();
    $consulta = $conexion->prepare(query: $sql);

    Logger::info(
      mensaje: PdoDebugger::show(raw_sql: $sql, parameters: $params),
    );

    $consulta->execute(params: $params);

    $data = $transformador($consulta);

    $consulta->closeCursor();

    return $data;
  }

  public static function conectar(): PDO
  {
    if (is_null(static::$conexion)) {
      static::$conexion = new PDO(
        dsn: "mysql:dbname={$_ENV['DB_NAME']};host=127.0.0.1;charset=utf8mb4",
        username: $_ENV['DB_USER'],
        password: $_ENV['DB_PASS'],
        options: [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
      );
    }

    return static::$conexion;
  }

  public function desconectar(): void
  {
    static::$conexion = null;
  }

  public function __clone()
  {
  }

  public function __wakeup()
  {
  }
}
