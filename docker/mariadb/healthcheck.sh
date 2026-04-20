#!/usr/bin/env sh

set -eu

if [ -n "${MARIADB_ROOT_PASSWORD:-}" ]; then
	mariadb-admin ping -h 127.0.0.1 -uroot -p"${MARIADB_ROOT_PASSWORD}" --silent
else
	mariadb-admin ping -h 127.0.0.1 -uroot --silent
fi

