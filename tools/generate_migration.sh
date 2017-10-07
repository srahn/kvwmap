#!/bin/bash

if [ "$1" != "m" -a "$1" != "p" ]; then
	echo "generate_migration {m|p} {kvwmap|<pluginname>} [migrationname]"
	exit 1
else
	if [ "$1" = "m" ]; then
		type=mysql
	else type=postgresql
	fi
	timestamp="$(date +"%Y-%m-%d_%H-%M-%S")"
	content="BEGIN;\n\n\n\nCOMMIT;"
	if [ "$2" == "kvwmap" ]; then
		echo "Erzeuge kvwmap migration: ../layouts/db/$type/schema/${timestamp}_${3}.sql"
		echo -e $content > ../layouts/db/$type/schema/${timestamp}_${3}.sql
	elif [ "$2" != "" ]; then
		echo "Erzeuge plugin migration: ../plugins/$2/db/$type/schema/${timestamp}_${3}.sql"
		echo -e $content > ../plugins/$2/db/$type/schema/${timestamp}_${3}.sql
	fi
fi
