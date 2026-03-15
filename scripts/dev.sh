#!/bin/bash

reinstall() {
    docker compose -f docker-compose-dev.yml down

    [ -f .env ] && ENV_SET=true || ENV_SET=false

    if [ "$ENV_SET" = "false" ]; then
        cp .env.example .env
    fi

    docker compose -f docker-compose-dev.yml up --build -d

    docker compose -f docker-compose-dev.yml exec app composer install
    docker compose -f docker-compose-dev.yml exec app npm i

    if [ "$ENV_SET" = "false" ]; then
        docker compose -f docker-compose-dev.yml exec app php artisan key:generate
        echo "Update environment file manually where needed"
    fi

    docker compose -f docker-compose-dev.yml exec app php artisan migrate
    docker compose -f docker-compose-dev.yml exec -d app npm run dev
}

up() {
    [ -f .env ] && ENV_SET=true || ENV_SET=false

    if [ "$ENV_SET" = "false" ]; then
        reinstall
        return
    fi

    docker compose -f docker-compose-dev.yml start
    docker compose -f docker-compose-dev.yml exec -d app npm run dev
}

stop() {
    docker compose -f docker-compose-dev.yml stop
}

shell() {
    docker compose -f docker-compose-dev.yml exec --user $(id -u) app bash
}

logs() {
    docker compose -f docker-compose-dev.yml logs -f
}

COMMAND=${1:-up}

if declare -f "$COMMAND" > /dev/null; then
    "$COMMAND"
else
    echo "'$COMMAND' is not a valid command."
fi
