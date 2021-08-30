# PWD2021 TP Final

Este trabajo consiste en completar el desarrollo de la aplicación de juegos de generala definida durante la cursada del 2021. Con este trabajo podrán confirmar conocimientos como:

- Manejo de IPOO estándar:
  - Herencia y composición.
  - Tipado de atributos, parámetros y variables.
  - Uso del estándar de namespaces.
  - Uso de tests unitarios con **PHPUnit**.
  - Separación de conceptos según las responsabilidades.
- Patrón MVC (Modelo, Vista, Controlador).
  - Migraciones de base de datos como herramienta de versionado de cambios de BD.
  - Hidrataciones como herramienta de generación de datos de prueba.
- Manejo de SQL para persistencia y lectura de datos.
- **Composer** como administrador de dependencias de php.
- **Prettier** (a través de nodejs) como herramienta de formateo de código.
- **VSCode** + plugins para facilitar el desarrollo.
- **Github** y **Git** como almacenamiento del repositorio y ejecución de los tests (a través de docker).
- **Slim** como _microservicio_ para una api rest (servidor de datos).
- (_Nuevo_) **PHPStan** como herramienta de análisis estático del código.
- (_Nuevo_) Conceptos de HTTP y comunicación asincrónica.
- (_Nuevo_) Uso del comando `curl` para probar llamadas HTTP a la API JSON.

## Comandos necesarios

Todas las recomendaciones para manejar el trabajo las pueden encontrar en el archivo de [AYUDA.md](AYUDA.md).

## Requisitos para la entrega y evaluación del trabajo

- Resolver todos los objetivos.
- Verificar que los tests pasen.
- Respetar las reglas definidas en la teoría respecto a los namespaces. Ignorar el uso correcto de mayúsculas y minúsculas será motivo para pedir rehacer el trabajo.
- Manejar bien los errores esperados.
- Completar todos los objetivos del TP.

## Aspectos a considerar

- Todas las clases **DAO** deben implementar la interfaz **DaoInterface**.
- Las clases DAO deben ser las únicas clases que ejecuten código SQL.
- Todas las clases **Controlador** deben implementar la interfaz **ControladorInterface**.
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

- La carpeta **Rutas** no necesita ser editada. En ella están configuradas todas las rutas del servidor, tanto de la API de datos, como también para las vistas.

## Actualizaciones

### 2021-08-30

- Agrega tests y consigna para crear el controlador de la generala.
- Actualiza las clases **Generala** y **JuegoAbstracto** para que puedan cumplir con lo necesario para que funcione el controlador.
- La generala deja de imprimir por consola el resultado de cada ronda. En cambio ahora devuelve un arreglo asociativo para que el cliente (futuro) pueda mostrar el resultado de la ronda.

## Objetivos principales del práctico

- Hacer pasar todos los tests.
- Crear `JugadorControlador` e implementar los métodos de la interface `ControladorInterface`.
- Crear `GeneralaControlador` e implementar los métodos de la interface `ControladorInterface`.
  - El método `actualizar` debería lanzar un error de que no está implementado.
  - En reemplazo de un único método "actualizar", `GeneralaControlador` deberá implementar los siguientes métodos estáticos extras (por fuera de la interface):
    - `iniciar(string $id): array` :: inicia una partida de generala según el id recibido por parámetro y la persiste. Por último devuelve la generala serializada.
    - `pausar(string $id): array` :: pausa una partida de generala según el id recibido por parámetro y la persiste. Por último devuelve la generala serializada.
    - `reanudar(string $id): array` :: reanuda una partida de generala según el id recibido por parámetro y la persiste. Por último devuelve la generala serializada.
    - `resetear(string $id): array` :: resetea una partida de generala según el id recibido por parámetro y la persiste. Por último devuelve la generala serializada.
    - `realizarRonda(string $id): array` :: realiza una ronda de una partida de generala según el id recibido por parámetro y la persiste. Por último devuelve la siguiente estructura: un arreglo asociativo con dos claves: (1) **generala**, con los datos serializados de la misma; y (2) **resultado**, con los resultados de la ronda actual, obtenidos al invocar el método `realizarRonda` de la generala. NOTA: el método `realizarRonda` ya no imprime por consola el resultado, y en vez de eso, lo devuelve como datos, para que el cliente pueda mostrarlo.

## Objetivos secundarios

- Actualmente no hay objetivos secundarios.
