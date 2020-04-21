#!/bin/bash

# Install `pre-commit` git hook that runs all the scripts in `pre-commit.d`

cd "$(dirname "$0")"

echo 'Copying pre-commit script'
cp pre-commit ../.git/hooks

echo 'Symlinking pre-commit.d'
ln -s ../../githooks/pre-commit.d ../.git/hooks