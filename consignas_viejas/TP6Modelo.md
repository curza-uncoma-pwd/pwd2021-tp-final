# PWD2021 TP6: Capa del Modelo

- Link a la teoría: [https://hackmd.io/@lgraziani2712/r1zEXhWOd](https://hackmd.io/@lgraziani2712/r1zEXhWOd)

El objetivo de este práctico es integrar todo lo aprendido en la unidad dos al mismo tiempo que nos introducimos en el patrón MVC (Modelo-Vista-Controlador) a través de la capa del Modelo. Este trabajo consiste en implementar las operaciones que se necesitan para poder jugar a la generala.

**Importante**: tengan la teoría siempre a mano como machete. Recuerden que no necesitan memorizarlo todo.

## Comandos necesarios

Para poder programar bien, deben correr el siguiente comando en la raíz del proyecto:

```
yarn

composer install
```

Se encargará de instalar la herramienta de autoformateo de PHP. Ya con esto, podrán programar y despreocuparse del formato del código.

## Requisitos para la entrega y evaluación del trabajo

- Resolver todos los objetivos.
- Verificar que los tests pasen.
- Respetar las reglas definidas en la teoría respecto a los namespaces. Ignorar el uso correcto de mayúsculas y minúsculas será motivo para pedir rehacer el trabajo.
- La nota del TP tendrá un valor aprobado/desaprobado que se tendrá en cuenta para la promoción de la materia.
- Manejar bien los errores esperados.
- Completar todos los objetivos principales del TP.
- Completar _al menos_ 1 (un) objetivo secundario.

## Contexto del problema

Un cliente nos pidió finalizar la implementación del juego de la generala. Quiere que esté orientado a objetos para poder expandirlo en algún futuro, ya sea para guardarlo en alguna base de datos o para crear una interfaz web. El cliente quiere que haya 3 actores principales en este sistema: el Dado, el Jugador y el Cubilete. Estos 3 actores deben estar coordinados por un último actor principal, el Juego, es decir, la Generala.

Las normas del juego así como los pasos que debe considerarse para la realización de una partida ya están definidos por el cliente, en un intento personal de desarollarlo. Sin embargo no tiene el tiempo necesario para finalizar su desarrolo. A continuación se detalla la documentación definida a partir de la reunión con el cliente respecto a lo que queda por implementar.

## Aspectos a considerar

- Todas las clases del modelo deben extender de la clase abstracta **ModeloBase**.
- Pasar parámetros utilizando el sistema de argumentos nominales. Documentación: https://www.php.net/releases/8.0/en.php#named-arguments. Ejemplo:

  ```php
  function metodo(int $edad, string $nombre)
  {
  }

  // esto es más entendible
  metodo(edad: 24, nombre: 'Norberto');
  // que esto otro (ambos son válidos)
  metodo(24, 'Norberto');
  ```

  Esta forma de pasar argumentos en la invocación de una función o método ayuda a saber qué significa cada parámetro en vez de estar memorizando el significado de cada uno de ellos según la posición.

## Objetivos principales del práctico

- Utilizar la función `Logger::info(ModeloBase $modelo, string $mensaje)` como herramienta de notificación de las acciones que ocurren durante la partida.
- Implementar la clase **Jugador**.
  - Atributos:
    - **nombre**: string y privado.
  - Métodos
    - El constructor debe seguir la firma `__construct(?string $id = null, string $nombre);`
    - `nombre(): string`. Es el getter del nombre.
    - `realizarTurno(Cubilete $cubilete)`: se debe encargar de lanzar los dados del cubilete.
      - Se recomienda utilizar la función `Logger::info()` para informar que el jugador empezó su turno.
  - No tiene tests unitarios.
- Implementar la clase **Dado**.
  - Atributos:
    - **valorMinimo**: int y privado.
    - **valorMaximo**: int y privado.
    - **caraVisible**: int y privado.
    - **estado**: string y privado. Puede almacenar cualquiera de los atributos constantes que ya están definidos en la clase.
  - Métodos:
    - El constructor debe seguir la firma:
      ```php
      function __construct(
        ?string $id = null,
        int $valorMinimo,
        int $valorMaximo,
      ) {
      }
      ```
      - debe asegurarse de que el estado esté en INICIAL.
      - debe asegurarse de setear el valor del atributo **caraVisible** en -1.
    - `estado(): string`: getter del atributo estado.
    - `rodar(): string`: debe cambiar el estado a `RODANDO`. Se recomienda usar la función `Logger::info()` para informar que el dado empezó a rodar. Este método no tiene ninguna verificación, no importa en qué estado está el dado, si se invoca, pasa al estado `RODANDO`.
    - `reposar(): void`: setea una cara visible aleatoria entre el valor mínimo y máximo y el estado en reposo.
      - Si el estado actual es **en reposo**, lanzar error `DadoEstadoError` con el código `DadoEstadoError::INTENTAR_REPOSAR_EN_REPOSO`.
      - El valor aleatorio lo pueden obtener con la función nativa `random_int(int $min, int $max): int`
    - `caraVisible(): int`: es el getter del atributo del mismo nombre.
      - Si el estado actual es **rondando**, lanzar error `DadoEstadoError` con el código `DadoEstadoError::INTENTAR_LEER_MIENTRAS_RUEDA`.
- Implementar la clase **Cubilete**.
  - Atributos:
    - **dados**: array y privado. Nota: pueden utilizar el siguiente PHPDoc (para agregar tipado y autocompletado) encima de la definición del atributo:
      ```php
      /** @var Array<Dado> */
      private array $dados;
      ```
    - **juego**: de tipo `JuegoAbstract`, privado. Almacena el juego que recibe como parámetro en el constructor. Es necesario para que podamos saber los valores mínimo y máximo de un dado durante la ejecución del método `resultado()` de esta clase.
  - Métodos:
    - El constructor debe seguir la siguiente firma:
      ```php
      __construct(?string $id = null, JuegoAbstracto $juego)
      ```
      - Debe inicializar el atributo **dados** e instanciar todos los dados necesarios. Para ello se debe tomar la información que ofrece la variable **$juego**. NOTA: buscar qué métodos hay en la clase **JuegoAbstracto** que le permitiría saber cuántos dados crear y con qué números como valor mínimo y máximo.
    - `lanzarDados(): void`:
      - Debe usar `Logger::info` para informar que se lanzaron los dados.
      - Debe hacer `rodar()` y `reposar()` todos los dados.
    - `resultado(): array`: debe devolver una dupla de arreglos.
      - Primer arreglo: representa la cantidad de veces que salió cada número.
      - Segundo arreglo: representa el valor de la sumatoria de las caras que salieron. Ej, si salió 3 veces el 5, en la posición 5 debería tener 15.
      - En estos dos arreglos deben haber tantas posiciones como caras tenga el dado + N. Esto se debe porque las posiciones entre **0** y **valorMinimo** del dado deben estar en **null**.

## Objetivos secundarios

> ATENCIÓN: si aún no empezaron con ningún objetivo secundario y ya está disponible el TP7, pueden dejar para más adelante esta parte del TP y pasar al TP7.

- **JuegoAbstracto**:
  - Agregar opciones para configuración de cantidad de jugadores mínimos y máximos.
    - Agregar tests relacionados a este objetivo (opcional).
- **Generala**:
  - Agregar lógica que lleve registro de cómo queda el puntaje de los jugadores.
  - Notificar quién ganó o el orden en que quedó cada jugador.
  - Agregar tests asociados a los objetivos anteriores (opcional).
- **Juego nuevo**:
  - Implementar un juego a gusto (basándose en la clase **Generala**) y utilizando las 3 clases bases de nuestro modelo (**Cubilete**, **Dado** y **Jugador**).

Es obligatorio que al menos uno de estos objetivos secundarios sea desarrollado por el alumno. Queda a criterio de cada quien elegir cuál hacer. NOTA: si quieren desarrollar otra cosa que no figura en ninguno de estos 3 objetivos secundarios, por favor consúlteme por el canal de dudas, que si lo considero adecuado, les doy el OK.
