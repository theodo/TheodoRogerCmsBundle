#!/bin/bash
set -xeu

# Reset test data
./reset-test-data.sh
./app/console cache:clear

phpunit -c app/
