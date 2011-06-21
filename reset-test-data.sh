#!/bin/bash

# variable fournie par Hudson
[ -n "${SYMFTTPD_PATH}" ] && export PATH="${PATH}:${SYMFTTPD_PATH}"
set -u

# symlinks
which mksymlinks2 && mksymlinks2
./checksamples.php ln

#test database
./dbtool.php

./app/console doctrine:database:drop --force --connection=test
./app/console doctrine:database:create --connection=test
./app/console doctrine:schema:create --em=test
./app/console doctrine:fixtures:load --em=test

./app/console cache:clear


