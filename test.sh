#!/bin/bash
./vendor/bin/phpunit --color --bootstrap vendor/autoload.php --coverage-text --whitelist src tests
