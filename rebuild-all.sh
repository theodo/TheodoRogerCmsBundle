#!/bin/bash

echo "# Build entities";
app/console doctrine:generate:entities Sadiant

echo "# Load dev environnement";

app/console doctrine:database:drop --force
app/console doctrine:database:create
app/console doctrine:schema:create
app/console doctrine:fixtures:load

echo "\n# Load test environnement";

app/console doctrine:database:drop --force --connection=test
app/console doctrine:database:create --connection=test
app/console doctrine:schema:create --em=test
app/console doctrine:fixtures:load --em=test

phpunit -c app
