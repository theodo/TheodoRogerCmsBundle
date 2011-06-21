#!/bin/bash
set -xeu

# Reset test data
./reset-test-data.sh

phpunit -c app/
