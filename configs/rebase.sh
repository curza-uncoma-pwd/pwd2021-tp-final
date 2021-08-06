#!/bin/sh
set -e

git fetch upstream
git merge upstream/main --allow-unrelated-histories
git push

yarn && composer install
