#!/usr/bin/env bash

export XDEBUG_CONFIG="remote_enable=1 idekey=PHPSTORM remote_autostart=1"
export PHP_IDE_CONFIG="serverName=localhost"

$(which php) "$@"