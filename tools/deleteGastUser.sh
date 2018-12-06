#!/bin/bash
# This script delete all gast user older than 1 day

CONFIG_FILE="$(dirname $0)/../config.php"
#echo "CONFIG_FILE: ${CONFIG_FILE}"

MYSQL_HOST=$(grep "'MYSQL_HOST'" $CONFIG_FILE | cut -d "'" -f 4)
#echo "MYSQL_HOST: ${MYSQL_HOST}"

MYSQL_DATABASE=$(grep "$\dbname=" $CONFIG_FILE | cut -d "'" -f 2)
#echo "MYSQL_DATABASE: ${MYSQL_DATABASE}"

MYSQL_USER=$(grep "'MYSQL_USER'" $CONFIG_FILE | cut -d "'" -f 4)
#echo "MYSQL_USER: ${MYSQL_USER}"

MYSQL_PASSWORD=$(grep "'MYSQL_PASSWORD'" $CONFIG_FILE | cut -d "'" -f 4)
#echo "MYSQL_PASSWORD: ${MYSQL_PASSWORD}"

CURRENT_TIME=$(date -d yesterday '+%Y-%m-%d %H:%M:%S')
#echo "CURRENT_TIME: ${CURRENT_TIME}"

SQL="DELETE FROM user WHERE Name LIKE 'gast' AND Vorname LIKE 'gast' AND password_setting_time < '${CURRENT_TIME}'"
#echo "SQL: ${SQL}"

mysql --host=${MYSQL_HOST} --user=${MYSQL_USER} --password=${MYSQL_PASSWORD} -e "${SQL}" ${MYSQL_DATABASE} > /dev/null 2>&1
#echo "exec: mysql --host=${MYSQL_HOST} --user=${MYSQL_USER} --password=${MYSQL_PASSWORD} -e \"${SQL}\" ${MYSQL_DATABASE} > /dev/null 2>&1"
