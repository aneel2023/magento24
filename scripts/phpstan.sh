#!/usr/bin/env bash

PROJECT_PATH="$(pwd)"

echo "currently in $PROJECT_PATH"

cd "$PROJECT_PATH/magento"

/usr/local/bin/composer install --dry-run --prefer-dist --no-progress &> /dev/null

COMPOSER_COMPATIBILITY=$?

echo "Composer compatibility: $COMPOSER_COMPATIBILITY"

set -e

if [ $COMPOSER_COMPATIBILITY = 0 ]
then
	/usr/local/bin/composer install --prefer-dist --no-progress
else
  echo "using composer v1"
  php7.2 /usr/local/bin/composer self-update --1
	/usr/local/bin/composer install --prefer-dist --no-progress
fi

if [ -d "$PROJECT_PATH/magento/build/tools" ]
then
	echo "PHPStan $PROJECT_PATH/magento/build/tools exists."
	cd $PROJECT_PATH/magento/build/tools
	composer install
fi

if [ -d "$PROJECT_PATH/magento/build/tools/bin" ]
then
	echo "PHPStan BIN ###### $PROJECT_PATH/magento/build/tools/bin exists."
fi

$PROJECT_PATH/magento/build/tools/bin/phpstan analyse --level=8 $PROJECT_PATH/magento/$INPUT_EXEC_PATH

cd $PROJECT_PATH