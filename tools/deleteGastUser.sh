#!/bin/bash
# This script delete all gast user older than 1 day

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

CURRENT_TIME=$(date -d yesterday '+%Y-%m-%d %H:%M:%S')
#echo "CURRENT_TIME: ${CURRENT_TIME}"

SQL="DELETE FROM user WHERE Name LIKE 'gast' AND Vorname LIKE 'gast' AND password_setting_time < '${CURRENT_TIME}';
SET @m = (SELECT MAX(id) + 1 FROM user); 
SET @s = CONCAT('ALTER TABLE user AUTO_INCREMENT=', @m);
PREPARE stmt1 FROM @s;
EXECUTE stmt1;
DEALLOCATE PREPARE stmt1;"
#echo "SQL: ${SQL}"

mysql --host=${MYSQL_HOST} --user=${MYSQL_USER} --password=${MYSQL_PASSWORD} -e "${SQL}" ${MYSQL_DATABASE} > /dev/null 2>&1
#echo "exec: mysql --host=${MYSQL_HOST} --user=${MYSQL_USER} --password=${MYSQL_PASSWORD} -e \"${SQL}\" ${MYSQL_DATABASE} > /dev/null 2>&1"
