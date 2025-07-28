#!/bin/bash
# This script delete all gast user older than 1 day

CREDENTIALS_FILE="$(dirname $0)/../credentials.php"
CONFIG_FILE="$(dirname $0)/../config.php"
#echo "CONFIG_FILE: ${CONFIG_FILE}"

POSTGRES_HOST=$(grep "POSTGRES_HOST'," $CREDENTIALS_FILE | cut -d "'" -f 4)
#echo "POSTGRES_HOST: ${POSTGRES_HOST}"

POSTGRES_DATABASE=$(grep "POSTGRES_DBNAME'" $CREDENTIALS_FILE | cut -d "'" -f 4)
#echo "POSTGRES_DATABASE: ${POSTGRES_DATABASE}"

POSTGRES_USER=$(grep "POSTGRES_USER'" $CREDENTIALS_FILE | cut -d "'" -f 4)
#echo "POSTGRES_USER: ${POSTGRES_USER}"

POSTGRES_PASSWORD=$(grep "POSTGRES_PASSWORD'" $CREDENTIALS_FILE | cut -d "'" -f 4)
#echo "POSTGRES_PASSWORD: ${POSTGRES_PASSWORD}"

LOGPATH=$(grep "LOGPATH'" $CONFIG_FILE | cut -d "'" -f 4)

DEBUGFILE=$(grep "DEBUGFILE'" $CONFIG_FILE | cut -d "'" -f 4)

if [[ "$DEBUGFILE" == */* ]]; then
    REAL_LOGPATH=$LOGPATH$(dirname "$DEBUGFILE")"/"
else
    REAL_LOGPATH=$LOGPATH
fi

CURRENT_TIME=$(date -d yesterday '+%Y-%m-%d %H:%M:%S')
#echo "CURRENT_TIME: ${CURRENT_TIME}"

SQL="SELECT id, login_name FROM kvwmap.user WHERE name LIKE 'gast' AND vorname LIKE 'gast' AND password_setting_time < '${CURRENT_TIME}'";
PGPASSWORD="${POSTGRES_PASSWORD}" psql --host=${POSTGRES_HOST} --username=${POSTGRES_USER} -d ${POSTGRES_DATABASE} -t -A -F '|' -c "${SQL}" | while IFS='|' read -r id login_name; do
    rm ${REAL_LOGPATH}mapserver${id}.log
    rm ${REAL_LOGPATH}${login_name}_debug.htm
done

SQL="DELETE FROM kvwmap.user WHERE name LIKE 'gast' AND vorname LIKE 'gast' AND password_setting_time < '${CURRENT_TIME}';
SELECT setval('kvwmap.user_id_seq', (select max(id) from kvwmap.user), false);"
#echo "SQL: ${SQL}"

#psql --host=${POSTGRES_HOST} --username=${POSTGRES_USER} --password=${POSTGRES_PASSWORD} -c "${SQL}" ${POSTGRES_DATABASE} > /dev/null 2>&1
PGPASSWORD="${POSTGRES_PASSWORD}" psql --host=${POSTGRES_HOST} --username=${POSTGRES_USER} -d ${POSTGRES_DATABASE} -c "${SQL}" > /dev/null 2>&1
#echo "exec: psql --host=${POSTGRES_HOST} --user=${POSTGRES_USER} --password=${POSTGRES_PASSWORD} -c \"${SQL}\" ${POSTGRES_DATABASE} > /dev/null 2>&1"
