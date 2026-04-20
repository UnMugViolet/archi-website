#!/usr/bin/env sh

set -eu

if [ -n "$${MARIADB_ROOT_PASSWORD}" && [ -n "$${MARIADB_ROOT_HOST}"]]; then
	mariadb-admin ping -h 127.0.0.1 -u"$${MARIADB_ROOT_HOST}" -p"$${MARIADB_ROOT_PASSWORD}" --silent;
else
	mariadb-admin ping -h 127.0.0.1 -u"$${MARIADB_ROOT_HOST}" --silent;
fi

