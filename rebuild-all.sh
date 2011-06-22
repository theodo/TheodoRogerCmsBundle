#!/bin/bash

echo "# Build entities";
php app/console doctrine:generate:entities Sadiant

echo "# Load dev environnement";

php app/console doctrine:database:drop --force
php app/console doctrine:database:create
php app/console doctrine:schema:create
php app/console doctrine:fixtures:load

echo "\n# Load test environnement";

php app/console doctrine:database:drop --force --connection=test
php app/console doctrine:database:create --connection=test
php app/console doctrine:schema:create --em=test
php app/console doctrine:fixtures:load --em=test

php app/console cache:clear

phpunit -c app
