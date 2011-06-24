#!/bin/bash

# variable fournie par Jenkins
[ -n "${SYMFTTPD_PATH}" ] && export PATH="${PATH}:${SYMFTTPD_PATH}"
set -u

# symlinks
if which mksymlinks2; then
  mksymlinks2
elif which mksymlinks; then
  mksymlinks
fi

php checksamples.php ln

#test database
php dbtool.php

php app/console doctrine:generate:entities SadiantCmsBundle

php app/console doctrine:database:drop --force --connection=test
php app/console doctrine:database:create --connection=test
php app/console doctrine:schema:create --em=test
php app/console doctrine:fixtures:load --em=test

php app/console cache:clear


