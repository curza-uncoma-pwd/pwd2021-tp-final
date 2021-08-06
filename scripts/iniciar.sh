#!/bin/sh

php -dxdebug.mode=debug -dxdebug.start_with_request=yes -S localhost:8080 -t public
