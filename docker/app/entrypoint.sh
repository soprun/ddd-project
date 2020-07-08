#!/bin/sh

set -e

[[ ! -e /.dockerenv ]] && exit 0

composer check-platform-reqs --no-interaction

if [[ ! -f .env.local ]] ; then
    composer dump-env ${APP_ENV} --quiet
fi

exec "$@"
