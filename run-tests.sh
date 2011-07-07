#!/bin/bash
set -xeu

# Reset test data
./reset-test-data.sh
php app/console cache:clear

phpunit -c app/
