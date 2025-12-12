# proyecto-fp

# Asegurece de tener Instalado XAMPP, Symfony, Composer, Node

# Para desplegar la Pagina Web
Primero tendrás que usar el siguiente comando para instalar las dependencias:
 composer install

Luego tendran que usar los siguientes comando en el terminal para levantar la Base de Datos y aplicar la informacion en ella
(si utilizan otra base de datos vaya al fichero .env y comente la linea 29 y mire cual de las otras lineas corresponden a su database y descomentela)
# Para crear la Base de datos
 symfony console doctrine:database:create

# Para crear las tablas
 symfony console doctrine:migrations:migrate

# Para implentar la informacion en las tablas
 symfony console doctrine:fixtures:load

# Si tienes que reiniciar la informacion de las tablas
 symfony console doctrine:schema:drop --force
 symfony console doctrine:schema:update --force
 symfony console doctrine:fixtures:load -n