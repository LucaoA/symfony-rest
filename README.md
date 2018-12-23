Prerequisites
===
* Docker-ce
* Docker compose

APP
===

## Services exposed outside your environment ##

You can access your application via **`localhost`**, if you're running the containers directly, or through **``** when run on a vm. nginx and mailhog both respond to any hostname, in case you want to add your own hostname on your `/etc/hosts` 

Service|Address outside containers
------|---------|-----------
Webserver|[localhost:1994](http://localhost:1994)
MySQL|**host:** `localhost`; **port:** `1996`

## Hosts within your environment ##

You'll need to configure your application to use any services you enabled:

Service|Hostname|Port number
------|---------|-----------
php-fpm|php-fpm|9000
MySQL|mysql|3306 (default)
Memcached|memcached|11211 (default)

You need these commands to start the project DEV:
 * docker-compose up 
 * docker-compose exec php-fpm bin/console doctrine:schema:validate to Validate the mapping files.
 * docker-compose exec php-fpm bin/console doctrine:schema:update to Executes (or dumps) the SQL needed to update the database schema to match the current mapping metadata.    




A Symfony project created on December 21, 2018, 11:09 pm.
