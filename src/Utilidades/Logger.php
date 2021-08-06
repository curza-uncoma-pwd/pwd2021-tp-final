<?php

namespace Raiz\Utilidades;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Raiz\Modelos\ModeloBase;

final class Logger
{
  private static MonologLogger $instancia;

  public static function info(string $mensaje, ?ModeloBase $modelo = null): void
  {
    $logger = self::instancia();

    if (!is_null(value: $modelo)) {
      $nombreDelaClase = get_class(object: $modelo);
    }

    $logger->info(
      message: is_null(value: $modelo)
        ? $mensaje
        : "[$nombreDelaClase#{$modelo->id()}] $mensaje",
    );
  }

  private static function instancia(): MonologLogger
  {
    if (isset(self::$instancia)) {
      return self::$instancia;
    }
    $formato = "%message%\n";
    $formateador = new LineFormatter(
      format: $formato,
      dateFormat: 'Y-m-d H:i:s',
    );
    $manejadorDeStreamDeTexto = new StreamHandler(
      stream: 'php://stdout',
      level: MonologLogger::INFO,
    );
    $manejadorDeStreamDeTexto->setFormatter(formatter: $formateador);

    self::$instancia = new MonologLogger(name: 'principal');

    self::$instancia->pushHandler(handler: $manejadorDeStreamDeTexto);

    return self::$instancia;
  }
}
