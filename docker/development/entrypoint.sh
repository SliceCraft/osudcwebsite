#!/bin/bash

HOST_UID=$(stat -c "%u" /var/www)
HOST_GID=$(stat -c "%g" /var/www)

if [ "$HOST_UID" != "0" ]; then
    echo "Updating permissions"
    usermod -u $HOST_UID www-data
    groupmod -g $HOST_GID www-data
fi

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

exec "$@"
