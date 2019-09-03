#!/bin/bash
usage() {
  echo "Usage: copy_kvwmap.sh [OPTIONS] SOURCE TARGET"
  echo "SOURCE kann folgende Werte haben:"
  echo "  dev   Kopiert von Entwicklungsversion nach TARGET"
  echo "  demo  Kopiert von Demoversion nach TARGET"
  echo "  prod  Kopiert von Produktionsversion nach TARGET"
  echo "TARGET kann folgende Werte haben und muss sich von SOURCE unterscheiden"
  echo "  prod  Kopiert von SOURCE nach Produktionsversion"
  echo "  dev   Kopiert von SOURCE nach Entwicklungsversion"
  echo "  demo  Kopiert von SOURCE nach Demoversion"
  echo "  test  Kopiert von SOURCE nach Testversion"
  echo "Fälle von TARGET_DATA_HANDLER"
  echo "  none mache nichts"
  echo "  copy Lösche TARGET_DATA_DIR und Kopiere von SOURCE_DATA_DIR nach TARGET_DATA_DIR"
  echo "  link Lösche Verzeichnis SOURCE_DATA_DIR wenn es ein Verzeichnis ist."
  echo "       Lösche Link SOURCE_DATA_DIR wenn es ein Link ist"
  echo "       Lege Link SOURCE_DATA_DIR an der auf TARGET_DATA_DIR zeigt"
  echo "Wie das kvwmap Verzeichnis und die Datenbanken der jeweiligen Versionen heißen ist der Datei copy_kvwmap.config.json.sample beschrieben und kann mit einer Datei copy_kvwmap.config.json überschieben werden."
  echo ""
  echo "Optionen:"
  echo "  -h --help Hilfe"
}

exec_sql() {
  echo "Execute: psql -h $PGSQL_SERVER -U $PGSQL_USER -c "${sql}" ${SOURCE_PGSQL_DBNAME}";
  psql -h $PGSQL_SERVER -U $PGSQL_USER -c "${sql}" ${SOURCE_PGSQL_DBNAME}
}

# Parameter abfragen
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

# Prüfe Eingangsparameter
if [ -z $1 ] ; then
  echo "SOURCE fehlt im 1. Parameter!"
  usage
  exit
else
  SOURCE=$1
fi

if [ -z $2 ] ; then
  echo "TARGET fehlt im 2. Parameter!"
  usage
  exit
else
  TARGET=$2
fi

# Setze konstante Variablen
WWW_DIR="/var/www"
APPS_DIR="${WWW_DIR}/apps"
DUMP_DIR="${WWW_DIR}/backups"
CREDENTIALS_FILE="${APPS_DIR}/${SOURCE_APP_NAME}/credentials.php"
CONFIG_FILE="${APPS_DIR}/${SOURCE_APP_NAME}/config.php"
MYSQL_SERVER="mysql"
MYSQL_USER=$(grep MYSQL_USER $CREDENTIALS_FILE | cut -d "'" -f 2)
#echo "MYSQL_USER: ${MYSQL_USER}"
MYSQL_PASSWORD=$(grep MYSQL_PASSWORD $CREDENTIALS_FILE | cut -d "'" -f 2)
#echo "MYSQL_PASSWORD: ${MYSQL_PASSWORD}"
PGSQL_SERVER="pgsql"
PGSQL_USER=$(grep POSTGRES_USER $CONFIG_FILE | cut -d "'" -f 4)
#echo "PGSQL_USER: ${PGSQL_USER}"
#echo "POSTGRES_PASSWORD wird aus .pgpass entnommen"
PGSQL_PASSWORD=$(grep POSTGRES_PASSWORD $CONFIG_FILE | cut -d "'" -f 4)
#echo "PGSQL_PASSWORD: ${PGSQL_PASSWORD}"
PGSQL_BIN="/usr/lib/postgresql/9.6/bin"
PGPASS_ENTRY="${PGSQL_SERVER}:5432:${TARGET_PGSQL_DBNAME}:${PGSQL_USER}:${PGSQL_PASSWORD}"
grep -qxF "${PGPASS_ENTRY}" ~/.pgpass || echo "${PGPASS_ENTRY}" >> ~/.pgpass

# Setzte variable Variablen
if [ -f copy_kvwmap.config.json ] ; then
  CONFIG_FILE=copy_kvwmap.config.json
else
  CONFIG_FILE=copy_kvwmap.config.json.sample
fi
SOURCE_APP_DIR="${APPS_DIR}/$(jq -r .$SOURCE.app_dir $CONFIG_FILE)"
SOURCE_DATA_DIR="${WWW_DIR}/$(jq -r .$SOURCE.data_dir $CONFIG_FILE)"
SOURCE_MYSQL_DBNAME="$(jq -r .$SOURCE.mysql_dbname $CONFIG_FILE)"
SOURCE_PGSQL_DBNAME="$(jq -r .$SOURCE.pgsql_dbname $CONFIG_FILE)"
TARGET_APP_DIR="${APPS_DIR}/$(jq -r .$TARGET.app_dir $CONFIG_FILE)"
TARGET_DATA_DIR="${WWW_DIR}/$(jq -r .$TARGET.data_dir $CONFIG_FILE)"
TARGET_DATA_HANDLER="$(jq -r .$TARGET.data_handler $CONFIG_FILE)"
TARGET_MYSQL_DBNAME="$(jq -r .$TARGET.mysql_dbname $CONFIG_FILE)"
TARGET_PGSQL_DBNAME="$(jq -r .$TARGET.pgsql_dbname $CONFIG_FILE)"

# Prüfe Variablen
if [ ! -d $SOURCE_APP_DIR ] ; then
  echo "SOURCE Apps-Verzeichnis ${SOURCE_APP_DIR} existiert nicht!"
  usage
  exit 1
fi

if [ ! -d $SOURCE_DATA_DIR} ] ; then
  echo "SOURCE Data-Verzeichnis ${SOURCE_DATA_DIR} existiert nicht!"
  usage
  exit 1
fi

read -s -p "Projekt $SOURCE_APP_DIR wirklich kopieren nach $TARGET_APP_DIR (j/n)? " -n 1 -r
echo $REPLY

if [[ ! $REPLY =~ ^[YyJj]$ ]]
then
  echo 'Ok dann brechen wir das hier ab.';
  [[ "$0" = "$BASH_SOURCE" ]] && exit 1 || return 1
fi
echo 'Ok dann gehts los...'

echo "Lösche app ${TARGET_APP_DIR} Verzeichnis"
if [ -d "${TARGET_APP_DIR}" ]; then rm -Rf ${TARGET_APP_DIR}; fi

echo "Kopiere kvwmap app Verzeichnis von ${SOURCE_APP_DIR} nach ${TARGET_APP_DIR}"
cp -R ${SOURCE_APP_DIR} ${TARGET_APP_DIR}
chown -R gisadmin.gisadmin ${TARGET_APP_DIR}
chmod -R g+w ${TARGET_APP_DIR}

echo "Ersetze Datenbankname ${SOURCE_MYSQL_DBNAME} durch ${TARGET_MYSQL_DBNAME} in credentials.php und config.php"
sed -i -e "s|${SOURCE_MYSQL_DBNAME}|${TARGET_MYSQL_DBNAME}|g" ${TARGET_APP_DIR}/credentials.php
sed -i -e "s|${SOURCE_MYSQL_DBNAME}|${TARGET_MYSQL_DBNAME}|g" ${TARGET_APP_DIR}/config.php

echo "Ersetze Datenbankname ${SOURCE_PGSQL_DBNAME} durch ${TARGET_PGSQL_DBNAME} in config.php"
sed -i -e "s|${SOURCE_PGSQL_DBNAME}|${TARGET_PGSQL_DBNAME}|g" ${TARGET_APP_DIR}/config.php

echo "Ersetze Web-Alias in config.php"
sed -i -e "s|${SOURCE_APP_NAME}/|${TARGET_APP_NAME}/|g" ${TARGET_APP_DIR}/config.php

echo "Sicherung der Datenbank ${SOURCE_PGSQL_DBNAME} in ${TARGET_PGSQL_DBNAME}.dump"
${PGSQL_BIN}/pg_dump -h $PGSQL_SERVER -U $PGSQL_USER -Fc -f ${DUMP_DIR}/${TARGET_PGSQL_DBNAME}.dump ${SOURCE_PGSQL_DBNAME}

echo "Schliesse idle Prozesse in ${TARGET_PGSQL_DBNAME}"
sql="
  SELECT
    pg_terminate_backend(pid) 
  FROM
    pg_stat_activity 
  WHERE
    datname='${TARGET_PGSQL_DBNAME}' AND
  state = 'idle'
"
exec_sql

echo "Aktualisierung der Testdatenbank."
echo "Lösche Datenbank ${TARGET_PGSQL_DBNAME}"
sql="
  DROP DATABASE IF EXISTS ${TARGET_PGSQL_DBNAME}
"
exec_sql

echo "Erzeuge neue leere Datenbank ${TARGET_PGSQL_DBNAME}"
sql="
  CREATE DATABASE ${TARGET_PGSQL_DBNAME}
"
exec_sql

echo "Stelle Produktionsdatenbank ${SOURCE_PGSQL_DBNAME} in Datenbank ${TARGET_PGSQL_DBNAME} wieder her"
${PGSQL_BIN}/pg_restore -h $PGSQL_SERVER -U $PGSQL_USER -Fc -d ${TARGET_PGSQL_DBNAME} ${DUMP_DIR}/${TARGET_PGSQL_DBNAME}.dump

echo "Lösche Testdatenbank ${TARGET_MYSQL_DBNAME}, erzeuge eine neue leere und spiele die Sicherung von ${SOURCE_MYSQL_DBNAME} ein"
echo "DROP DATABASE IF EXISTS ${TARGET_MYSQL_DBNAME}" | mysql -h ${MYSQL_SERVER} -u kvwmap --password=${MYSQL_PASSWORD}
echo "CREATE DATABASE ${TARGET_MYSQL_DBNAME}" | mysql -h ${MYSQL_SERVER} -u kvwmap --password=${MYSQL_PASSWORD}
mysqldump -h ${MYSQL_SERVER} -u $MYSQL_USER --password=${MYSQL_PASSWORD} ${SOURCE_MYSQL_DBNAME} > ${DUMP_DIR}/${TARGET_MYSQL_DBNAME}.dump
mysql -h ${MYSQL_SERVER} -u $MYSQL_USER --password=${MYSQL_PASSWORD} ${TARGET_MYSQL_DBNAME} < ${DUMP_DIR}/${TARGET_MYSQL_DBNAME}.dump

echo "Nehme Ersetzungen in MySQL-Datenbank ${TARGET_MYSQL_DBNAME} vor"
echo "UPDATE layer SET \`connection\` = replace(connection, 'dbname=${SOURCE_PGSQL_DBNAME}', 'dbname=${TARGET_PGSQL_DBNAME}') WHERE \`connection\` LIKE '%dbname=${SOURCE_PGSQL_DBNAME}%'" | mysql -h ${MYSQL_SERVER} -u $MYSQL_USER --password=${MYSQL_PASSWORD} ${TARGET_MYSQL_DBNAME}
echo "UPDATE config SET \`value\` = '${TARGET_APP_NAME}/' WHERE \`name\` = 'APPLVERSION'" | mysql -h ${MYSQL_SERVER} -u $MYSQL_USER --password=${MYSQL_PASSWORD} ${TARGET_MYSQL_DBNAME}
echo "UPDATE config SET \`value\` = '${TARGET_DATA_DIR}/' WHERE \`name\` = 'SHAPEPATH'" | mysql -h ${MYSQL_SERVER} -u $MYSQL_USER --password=${MYSQL_PASSWORD} ${TARGET_MYSQL_DBNAME}
echo "UPDATE config SET \`value\` = '${TARGET_PGSQL_DBNAME}' WHERE \`name\` = 'POSTGRES_DBNAME'" | mysql -h ${MYSQL_SERVER} -u $MYSQL_USER --password=${MYSQL_PASSWORD} ${TARGET_MYSQL_DBNAME}

# Behandle Datenverzeichnis
# Fälle von TARGET_DATA_HANDLER
# none mache nichts
# copy Lösche TARGET_DATA_DIR und Kopiere von SOURCE_DATA_DIR nach TARGET_DATA_DIR
# link Lösche Verzeichnis SOURCE_DATA_DIR wenn es ein Verzeichnis ist.
#      Lösche Link SOURCE_DATA_DIR wenn es ein Link ist
#      Lege Link SOURCE_DATA_DIR an der auf TARGET_DATA_DIR zeigt
if [ "${TARGET_DATA_HANDLER}" == "copy" ]; then
  if [ -d "${TARGET_DATA_DIR}" ]; then 
    echo "Lösche TARGET Datenverzeichnis ${TARGET_DATA_DIR}"
    rm -Rf ${TARGET_DATA_DIR}; 
  fi
  echo "Kopiere SOURCE Datenverzeichnis ${SOURCE_DATA_DIR} nach TARGET Datenverzeichnis ${TARGET_DATA_DIR}"
  cp -R ${SOURCE_DATA_DIR} ${TARGET_DATA_DIR}
  chown -R gisadmin.gisadmin ${TARGET_DATA_DIR}
  chmod -R g+w ${TARGET_DATA_DIR}
else
  if [[ -L "${TARGET_DATA_DIR}" ]]; then
    # Target Dir ist ein LINK
    echo "Lösche Link auf Daten Verzeichnis ${TARGET_DATA_DIR}"
    rm ${TARGET_DATA_DIR}; 
  else
    if [ -d "${TARGET_DATA_DIR}" ]; then
      echo "Lösche Test Daten Verzeichnis ${TARGET_DATA_DIR}"
      rm -Rf ${TARGET_DATA_DIR};
    fi
  fi
  echo "Erzeuge Link von Testverzeichnis ${TARGET_DATA_DIR} auf Productionsverzeichnis ${SOURCE_DATA_DIR}"
  ln -s ${SOURCE_DATA_DIR} ${TARGET_DATA_DIR}
  chown -h gisadmin.gisadmin ${TARGET_DATA_DIR}
fi
