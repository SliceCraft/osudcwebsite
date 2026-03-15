#!/bin/bash

UID=$(stat -c "%u" /var/www)
GID=$(stat -c "%g" /var/www)

if [ "$UID" != "0" ]; then
    echo "Updating permissions"
    usermod -u $UID www-data
    groupmod -g $GID www-data
fi

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

exec "$@"
