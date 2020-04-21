#!/bin/bash
./vendor/bin/phpunit --bootstrap vendor/autoload.php --coverage-text --whitelist src tests
