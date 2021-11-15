start /B php -dxdebug.mode=debug -dxdebug.start_with_request=yes -S localhost:8080 ./src/server.php
start /B yarn dev
