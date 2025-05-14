#!/bin/bash
# This script delete all gast user older than 1 day

CONFIG_FILE="$(dirname $0)/../credentials.php"
#echo "CONFIG_FILE: ${CONFIG_FILE}"

POSTGRES_HOST=$(grep "POSTGRES_HOST'," $CONFIG_FILE | cut -d "'" -f 4)
#echo "POSTGRES_HOST: ${POSTGRES_HOST}"

POSTGRES_DATABASE=$(grep "POSTGRES_DBNAME'" $CONFIG_FILE | cut -d "'" -f 4)
#echo "POSTGRES_DATABASE: ${POSTGRES_DATABASE}"

POSTGRES_USER=$(grep "POSTGRES_USER'" $CONFIG_FILE | cut -d "'" -f 4)
#echo "POSTGRES_USER: ${POSTGRES_USER}"

POSTGRES_PASSWORD=$(grep "POSTGRES_PASSWORD'" $CONFIG_FILE | cut -d "'" -f 4)
#echo "POSTGRES_PASSWORD: ${POSTGRES_PASSWORD}"

CURRENT_TIME=$(date -d yesterday '+%Y-%m-%d %H:%M:%S')
#echo "CURRENT_TIME: ${CURRENT_TIME}"

SQL="DELETE FROM kvwmap.user WHERE name LIKE 'gast' AND vorname LIKE 'gast' AND password_setting_time < '${CURRENT_TIME}';
SELECT setval('kvwmap.user_id_seq', (select max(id) from kvwmap.user), false);"
#echo "SQL: ${SQL}"

psql --host=${POSTGRES_HOST} --username=${POSTGRES_USER} --password=${POSTGRES_PASSWORD} -c "${SQL}" ${POSTGRES_DATABASE} > /dev/null 2>&1
#echo "exec: psql --host=${POSTGRES_HOST} --user=${POSTGRES_USER} --password=${POSTGRES_PASSWORD} -c \"${SQL}\" ${POSTGRES_DATABASE} > /dev/null 2>&1"
