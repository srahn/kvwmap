#!/bin/bash
DIR=`dirname "$0"`
if [ "$1" != "d" -a "$1" != "s" ]; then
	echo "generate_migration {d|s} {kvwmap|<pluginname>} [migrationname]"
	echo "(d: data, s: schema)"
	exit 1
else
	type=postgresql
	if [ "$2" = "d" ]; then
		target=data
	else
		target=schema
	fi
	timestamp="$(date +"%Y-%m-%d_%H-%M-%S")"
	content="BEGIN;\n\n\n\nCOMMIT;"
	if [ "$3" == "kvwmap" ]; then
		echo "Erzeuge kvwmap migration: $PWD/${DIR}/../layouts/db/${type}/${target}/${timestamp}_${4}.sql"
		echo -e $content > $PWD/${DIR}/../layouts/db/${type}/${target}/${timestamp}_${4}.sql
	elif [ "$3" != "" ]; then
		echo "Erzeuge plugin migration: $PWD/${DIR}/../plugins/${3}/db/${type}/${target}/${timestamp}_${4}.sql"
		echo -e $content > $PWD/${DIR}/../plugins/${3}/db/${type}/${target}/${timestamp}_${4}.sql
	fi
fi
