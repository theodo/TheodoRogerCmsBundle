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

./checksamples.php ln

#test database
./dbtool.php

./app/console doctrine:generate:entities Sadiant

./app/console doctrine:database:drop --force --connection=test
./app/console doctrine:database:create --connection=test
./app/console doctrine:schema:create --em=test
./app/console doctrine:fixtures:load --em=test

./app/console cache:clear


