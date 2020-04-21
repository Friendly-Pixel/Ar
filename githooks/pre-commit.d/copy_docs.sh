#!/bin/bash
# This script is run from `.git/hook/pre-commit.d` directory, remember that in your paths
cd ../../../
./copy_docs.php
exit $?