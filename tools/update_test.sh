#!/bin/bash
WWW_DIR="/var/www"
APPS_DIR="${WWW_DIR}/apps"
DUMP_DIR="${WWW_DIR}/backups"

PRODUCTION_APP_NAME="webgis"
PRODUCTION_DATA_DIR="${WWW_DIR}/data"
PRODUCTION_PGSQL_DBNAME="kvwmapsp"

TEST_APP_NAME="webgis_demo"
TEST_DATA_DIR="${WWW_DIR}/data_demo"
TEST_DATA_AS_LINK="yes"
TEST_PGSQL_DBNAME="kvwmapsp_demo"

CREDENTIALS_FILE="${APPS_DIR}/${PRODUCTION_APP_NAME}/credentials.php"
CONFIG_FILE="${APPS_DIR}/${PRODUCTION_APP_NAME}/config.php"

PGSQL_SERVER="pgsql"
PGSQL_USER=$(grep POSTGRES_USER $CONFIG_FILE | cut -d "'" -f 4)
#echo "PGSQL_USER: ${PGSQL_USER}"
echo "POSTGRES_PASSWORD wird aus .pgpass entnommen"
PGSQL_PASSWORD=$(grep POSTGRES_PASSWORD $CONFIG_FILE | cut -d "'" -f 4)
#echo "PGSQL_PASSWORD: ${PGSQL_PASSWORD}"

PGPASS_ENTRY="${PGSQL_SERVER}:5432:${TEST_PGSQL_DBNAME}:${PGSQL_USER}:${PGSQL_PASSWORD}"
grep -qxF "${PGPASS_ENTRY}" ~/.pgpass || echo "${PGPASS_ENTRY}" >> ~/.pgpass

exec_sql() {
  echo "Execute: psql -h $PGSQL_SERVER -U $PGSQL_USER -c "${sql}" ${PRODUCTION_PGSQL_DBNAME}";
  psql -h $PGSQL_SERVER -U $PGSQL_USER -c "${sql}" ${PRODUCTION_PGSQL_DBNAME}
}

usage() {
  echo "Dieses Script kopiert alles von $PRODUCTION_APP_NAME nach $TEST_APP_NAME."
  echo "Das Script muss im Host-Rechner als root ausgeführt werden."
}

POSITIONAL=()
while [[ $# -gt 0 ]]
do
  key="$1"

  case $key in
      -h|--help)
      HELP="$2"
      usage
      exit 1
      shift # past argument
      shift # past value
      ;;
      -d|--directory)
      DIRECTORY="$2"
      shift # past argument
      shift # past value
      ;;
      --default)
      DEFAULT=YES
      shift # past argument
      ;;
      *)    # unknown option
      POSITIONAL+=("$1") # save it in an array for later
      shift # past argument
      ;;
  esac
done
set -- "${POSITIONAL[@]}" # restore positional parameters

read -s -p "Projekt $PRODUCTION_APP_NAME wirklich kopieren nach $TEST_APP_NAME (j/n)? " -n 1 -r
echo $REPLY

if [[ ! $REPLY =~ ^[YyJj]$ ]]
then
  echo 'Ok dann brechen wir das hier ab.';
  [[ "$0" = "$BASH_SOURCE" ]] && exit 1 || return 1
fi
echo 'Ok dann gehts los...'

echo "Lösche app ${TEST_APP_NAME} Verzeichnis"
if [ -d "${APPS_DIR}/${TEST_APP_NAME}" ]; then rm -Rf ${APPS_DIR}/${TEST_APP_NAME}; fi

echo "Kopiere kvwmap app Verzeichnis von ${PRODUCTION_APP_NAME} nach ${TEST_APP_NAME}"
cp -R ${APPS_DIR}/${PRODUCTION_APP_NAME} ${APPS_DIR}/${TEST_APP_NAME}
chown -R gisadmin.gisadmin ${APPS_DIR}/${TEST_APP_NAME}
chmod -R g+w ${APPS_DIR}/${TEST_APP_NAME}

echo "Ersetze Datenbankname ${PRODUCTION_PGSQL_DBNAME} durch ${TEST_PGSQL_DBNAME} in credentials.php"
sed -i -e "s|${PRODUCTION_PGSQL_DBNAME}|${TEST_PGSQL_DBNAME}|g" ${APPS_DIR}/${TEST_APP_NAME}/credentials.php

echo "Ersetze Web-Alias in config.php"
sed -i -e "s|${PRODUCTION_APP_NAME}/|${TEST_APP_NAME}/|g" ${APPS_DIR}/${TEST_APP_NAME}/config.php

echo "Sicherung der Datenbank ${PRODUCTION_PGSQL_DBNAME} in ${TEST_PGSQL_DBNAME}.dump"
pg_dump -h $PGSQL_SERVER -U $PGSQL_USER -Fc -f ${DUMP_DIR}/${TEST_PGSQL_DBNAME}.dump ${PRODUCTION_PGSQL_DBNAME}

echo "Schliesse idle Prozesse in ${TEST_PGSQL_DBNAME}"
sql="
  SELECT
    pg_terminate_backend(pid) 
  FROM
    pg_stat_activity 
  WHERE
    datname='${TEST_PGSQL_DBNAME}' AND
  state = 'idle'
"
exec_sql

echo "Aktualisierung der Testdatenbank."
echo "Lösche Datenbank ${TEST_PGSQL_DBNAME}"
sql="
  DROP DATABASE IF EXISTS ${TEST_PGSQL_DBNAME}
"
exec_sql

echo "Erzeuge neue leere Datenbank ${TEST_PGSQL_DBNAME}"
sql="
  CREATE DATABASE ${TEST_PGSQL_DBNAME}
"
exec_sql

echo "Stelle Produktionsdatenbank ${PRODUCTION_PGSQL_DBNAME} in Datenbank ${TEST_PGSQL_DBNAME} wieder her"
pg_restore -h $PGSQL_SERVER -U $PGSQL_USER -Fc -d ${TEST_PGSQL_DBNAME} ${DUMP_DIR}/${TEST_PGSQL_DBNAME}.dump

echo "Lösche Testdatenbank ${TEST_MYSQL_DBNAME}, erzeuge eine neue leere und spiele die Sicherung von ${PRODUCTION_MYSQL_DBNAME} ein"
echo "DROP DATABASE IF EXISTS ${TEST_MYSQL_DBNAME}" | mysql -h ${MYSQL_SERVER} -u kvwmap --password=${MYSQL_PASSWORD}
echo "CREATE DATABASE ${TEST_MYSQL_DBNAME}" | mysql -h ${MYSQL_SERVER} -u kvwmap --password=${MYSQL_PASSWORD}
mysqldump -h ${MYSQL_SERVER} -u $MYSQL_USER --password=${MYSQL_PASSWORD} ${PRODUCTION_MYSQL_DBNAME} > ${DUMP_DIR}/${TEST_MYSQL_DBNAME}.dump
mysql -h ${MYSQL_SERVER} -u $MYSQL_USER --password=${MYSQL_PASSWORD} ${TEST_MYSQL_DBNAME} < ${DUMP_DIR}/${TEST_MYSQL_DBNAME}.dump

echo "Nehme Ersetzungen in MySQL-Datenbank ${TEST_MYSQL_DBNAME} vor"
echo "UPDATE layer SET \`connection\` = replace(connection, 'dbname=${PRODUCTION_PGSQL_DBNAME}', 'dbname=${TEST_PGSQL_DBNAME}') WHERE \`connection\` LIKE '%dbname=${PRODUCTION_PGSQL_DBNAME}%'" | mysql -h ${MYSQL_SERVER} -u $MYSQL_USER --password=${MYSQL_PASSWORD} ${TEST_MYSQL_DBNAME}
echo "UPDATE config SET \`value\` = '${TEST_APP_NAME}/' WHERE \`name\` = 'APPLVERSION'" | mysql -h ${MYSQL_SERVER} -u $MYSQL_USER --password=${MYSQL_PASSWORD} ${TEST_MYSQL_DBNAME}
echo "UPDATE config SET \`value\` = '${TEST_DATA_DIR}/' WHERE \`name\` = 'SHAPEPATH'" | mysql -h ${MYSQL_SERVER} -u $MYSQL_USER --password=${MYSQL_PASSWORD} ${TEST_MYSQL_DBNAME}
echo "UPDATE config SET \`value\` = '${TEST_PGSQL_DBNAME}' WHERE \`name\` = 'POSTGRES_DBNAME'" | mysql -h ${MYSQL_SERVER} -u $MYSQL_USER --password=${MYSQL_PASSWORD} ${TEST_MYSQL_DBNAME}

if [ -z "TEST_DATA_AS_LINK" ]; then
  if [ -d "${TEST_DATA_DIR}" ]; then 
    echo "Lösche Test Daten Verzeichnis ${TEST_DATA_DIR}"
    rm -Rf ${TEST_DATA_DIR}; 
  fi
  echo "Kopiere Produktionsdatenverzeichnis ${PRODUCTION_DATA_DIR} nach Testverzeichnis ${TEST_DATA_DIR}"
  cp -R ${PRODUCTION_DATA_DIR} ${TEST_DATA_DIR}
  chown -R gisadmin.gisadmin ${TEST_DATA_DIR}
  chmod -R g+w ${TEST_DATA_DIR}
else
  if [[ -L "${TEST_DATA_DIR}" ]]; then 
    echo "Lösche Link auf Daten Verzeichnis ${TEST_DATA_DIR}"
    rm ${TEST_DATA_DIR}; 
  else
    if [ -d "${TEST_DATA_DIR}" ]; then
      echo "Lösche Test Daten Verzeichnis ${TEST_DATA_DIR}"
      rm -Rf ${TEST_DATA_DIR};
    fi
  fi
  echo "Erzeuge Link von Testverzeichnis ${TEST_DATA_DIR} auf Productionsverzeichnis ${PRODUCTION_DATA_DIR}"
  ln -s ${PRODUCTION_DATA_DIR} ${TEST_DATA_DIR}
  chown -h gisadmin.gisadmin ${TEST_DATA_DIR}
fi
