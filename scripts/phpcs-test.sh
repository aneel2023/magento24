#!/usr/bin/env bash

set -e

PROJECT_PATH="$(pwd)"

cd "$PROJECT_PATH/magento"

echo "Execute PHPCS FOR APP";
./build/tools/bin/phpcs --standard=$INPUT_STANDARD $PROJECT_PATH/magento/app/code/$INPUT_EXTENSION
