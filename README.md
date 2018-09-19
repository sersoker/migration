# Migration
Entendemos como migración a la ejecución de todas las tareas relacionadas con la preparación de la infrastructura. Por ejemlo, crear tablas en una BD, alterarlas, insertas datos iniciales, crear colas en un sistema de mensajería, etc.

Esta librería proporciona la base para ejecutar esas migraciones mediante comandos de consola [con el componente de symfony](https://symfony.com/doc/current/components/console.html).

## Crear una migración
Se recomienda crear un directorio ```migrations``` en la raíz del proyecto, y un subdirectorio con el tipo de migración que contendrá, por ejemplo ```mysql```,  ```rabbitmq```, o similares.
Por cada migración, creamos un fichero PHP con la declaración de una clase, que por convenio, debe llamarse igual que el fichero. Dicha clase __no debe estar en un namespace__.

Tu clase migración necesitará como dependencias en su constructor, lo mínimo necesario para hacer el trabajo. Por ejemplo, veámos como sería una migración de \PDO, para crear o borrar una tabla ```ejemlo```.
Deberá implementar la interfaz ```Pccomponentes\Migration\Migration```, con las tareas a realizar.

```php
<?php
declare(strict_types=1);

use Pccomponentes\Migration\Migration;

class PdoMigration implements Migration
{
    private $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function upOperation(): void
    {
        $this->connection->exec('
            CREATE TABLE example (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(30) NOT NULL
            )
        ');
    }

    public function downOperation(): void
    {
        $this->connection->exec('DROP TABLE example');
    }
}

```

## Ejecutar una migración
Para ejecutar la migración, tenemos dos caminos: Usando el framework de symfony, o creando nuestra propia aplicación symfony de consola.

### Con el framework symfony
Si nuestro proyecto cuenta con el framework de symfony, podemos meter el comando directamente al contenedor de dependencias, y marcarlo con el tag correspondiente, para que el kernel y el ejecutable ```console``` de symfony lo ejecute directamente.
Para añadirlo, sería modificar el fichero ```config/services.yml``` con esta información:
```yaml
pdo.connection:
    class: \PDO
    arguments:
        - 'mysql:dbname=testdb;host=localhost;port=3306'
        - 'user'
        - 'password'
        
pdo_migration_command:
    class: Pccomponentes\Migration\MigrationCommand
    arguments:
        - 'pdo'                                     # nombre del comando, que se concatenará a "migration:"
        - '%kernel.root_dir%/../migrations/pdo'     # Ruta al directorio de las migraciones para este comando
        - ['@pdo.connection']                       # Dependencias para construir nuestra migración
    tags:
        - { name: console.command }
```

### Creando nuestra propia aplicación
Para poder ejecutar el comando, previamente tenemos que generar una aplicación. Para ello, deberíamos crear un fichero PHP con el siguiente contenido, modificado lo necesario para adaptarlo a tu nuestro proyecto.
Como será un ejecutable de consola, lo llamaremos ```console``` sin extensión, y lo pondremos en un directorio ```bin``` en la raíz de tu proyecto.
```php
#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Pccomponentes\Migration\MigrationCommand;

$application = new Application();
$application->addCommands(
    [
        new MigrationCommand(
            'pdo',
            __DIR__ . '/../migration/pdo',
            [
                new \PDO('mysql:dbname=testdb;host=localhost;port=3306', 'user', 'password')
            ]
        )
    ]
);

$application->run();

```

## Ejecutar el comando

Para ejecutar el comando en modo UP, sería:
```$> bin/console migration:pdo --operation=up PdoMigration```

Si queremos ejecutar un DOWN:
```$> bin/console migration:pdo --operation=down PdoMigration```

Además es posible ejecutar múltiples ficheros del mismo tipo, en orden. Por ejemplo:

```$> bin/console migration:pdo --operation=up PdoMigration1 PdoMigration2 PdoMigration3```