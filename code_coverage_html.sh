#!/bin/bash
./vendor/bin/phpunit --color --bootstrap vendor/autoload.php --coverage-html coverage --whitelist src tests
