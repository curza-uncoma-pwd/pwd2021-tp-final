<?php

use Raiz\Bd\Auxiliadores\Migrador;

class HidrateParticipantes extends Migrador
{
  private string $tabla = 'participantes';

  public function up()
  {
    $jugadores = $this->read(sql: 'SELECT id FROM jugadores');
    $juegos = $this->read(sql: 'SELECT id FROM juegos');

    $sql = "INSERT INTO `$this->tabla` VALUES
    ('{$jugadores[0]['id']}', '{$juegos[0]['id']}'),
    ('{$jugadores[1]['id']}', '{$juegos[0]['id']}'),
    ('{$jugadores[2]['id']}', '{$juegos[0]['id']}'),

    ('{$jugadores[0]['id']}', '{$juegos[1]['id']}'),
    ('{$jugadores[3]['id']}', '{$juegos[1]['id']}'),
    ('{$jugadores[4]['id']}', '{$juegos[1]['id']}'),

    ('{$jugadores[0]['id']}', '{$juegos[2]['id']}'),
    ('{$jugadores[5]['id']}', '{$juegos[2]['id']}'),
    ('{$jugadores[6]['id']}', '{$juegos[2]['id']}');";

    $this->run($sql);
  }

  public function down()
  {
    $sql = "TRUNCATE TABLE `$this->tabla`;";

    $this->run($sql);
  }
}
