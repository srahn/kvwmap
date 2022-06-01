#!/bin/bash
# This script delete all archived user older than 2 years

CONFIG_FILE="$(dirname $0)/../credentials.php"
#echo "CONFIG_FILE: ${CONFIG_FILE}"

MYSQL_HOST=$(grep "MYSQL_HOST'," $CONFIG_FILE | cut -d "'" -f 4)
#echo "MYSQL_HOST: ${MYSQL_HOST}"

MYSQL_DATABASE=$(grep "MYSQL_DBNAME'" $CONFIG_FILE | cut -d "'" -f 4)
#echo "MYSQL_DATABASE: ${MYSQL_DATABASE}"

MYSQL_USER=$(grep "MYSQL_USER'" $CONFIG_FILE | cut -d "'" -f 4)
#echo "MYSQL_USER: ${MYSQL_USER}"

MYSQL_PASSWORD=$(grep "MYSQL_PASSWORD'" $CONFIG_FILE | cut -d "'" -f 4)
#echo "MYSQL_PASSWORD: ${MYSQL_PASSWORD}"

CURRENT_DATE=$(date -d yesterday '+%Y-%m-%d')
#echo "CURRENT_DATE: ${CURRENT_DATE}"

SQL="DELETE FROM user WHERE archived < DATE_SUB('${CURRENT_DATE}', INTERVAL 2 YEAR);"
#echo "SQL: ${SQL}"

mysql --host=${MYSQL_HOST} --user=${MYSQL_USER} --password=${MYSQL_PASSWORD} -e "${SQL}" ${MYSQL_DATABASE} > /dev/null 2>&1
#echo "exec: mysql --host=${MYSQL_HOST} --user=${MYSQL_USER} --password=${MYSQL_PASSWORD} -e \"${SQL}\" ${MYSQL_DATABASE} > /dev/null 2>&1"
