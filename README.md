# proyecto-fp

<!-- Para crear un proyecto nuevo de Symfony -->
symfony new [nombre_del_proyecto] --no-git

<!-- Para activar el servidor symfony -->
symfony serve -d

<!-- Para instalar Symfony Maker Bundle 
 (sirve para poder crear ficheros necesarios para la creacion del proyecto) -->
 composer require --dev maker

<!-- Para crear un controller para el proyecto -->
 symfony console make:controller

<!-- Para instalar ORM 
 (sirve para crear la database del proyecto) -->
composer require orm
<!-- Tras la instalacion tendras que ir al .env y buscar lasiguiente linea  --> 
DATABASE_URL="mysql://root:@127.0.0.1:3306/[nombre_de_la_database]?serverVersion=10.4.32-MariaDB&charset=utf8mb4"
<!-- Tras tener la linea editada usaremos este comando para crear la database -->
 symfony console doctrine:database:create

<!-- Para crear/actualizar una tabla en la database -->
 symfony console make:entity

<!-- Para crear/actualizar la migracion de datos para la database -->
 symfony console make:migration

<!-- Para iniciar la migracion de datos para la database -->
 symfony console doctrine:migrations:migrate

<!-- Para instalar fixtures
 (sirve para poder crear ficheros que generern datos para las tablas) -->
 composer require --dev ormfixtures

<!-- Para crear un fixture para la database -->
 symfony console make:fixtures
 
<!-- Para crear un fixture para la database -->
 symfony console doctrine:fixtures:load

<!-- Para iniciar el TailwindCSS -->
 npm run watch

<!-- Para instalar form 
 (sirve para crear formularios de las tablas de la database de nuestro proyecto) -->
 composer require form

<!-- Para crear un fixture para la database -->
 symfony console make:form

<!-- Para colocar y reiniciar los valores de lastabls -->
 symfony console doctrine:schema:drop --force
 symfony console doctrine:schema:update --force
 symfony console doctrine:fixtures:load -n

<!-- Crear todo el crud -->
symfony console make:crud

<!-- Añaade el twig -->
 composer require twig