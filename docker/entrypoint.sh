#!/bin/sh
set -e
PORT="${PORT:-80}"

if [ -f /etc/apache2/ports.conf ]; then
  sed -i "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
fi
if [ -f /etc/apache2/sites-enabled/000-default.conf ]; then
  sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-enabled/000-default.conf
fi

exec apache2-foreground
