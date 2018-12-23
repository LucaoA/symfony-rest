Prerequisites
===
* Docker-ce
* Docker compose

APP
===

You need these commands to start the project DEV:
 * docker-compose up 
 * docker-compose exec php-fpm bin/console doctrine:schema:validate to Validate the mapping files.
 * docker-compose exec php-fpm bin/console doctrine:schema:update to Executes (or dumps) the SQL needed to update the database schema to match the current mapping metadata.    




A Symfony project created on December 21, 2018, 11:09 pm.
