#!/bin/bash

#############
# functions
usage() {
  echo "Usage:"
  echo "import.sh [datei]"
  echo " datei ist die NAS-Datei, die eingelesen werden soll."
  echo " datei kann auch eine gz gepackte Datei sein."
  echo " Wenn der Dateiname Leerzeichen beinhaltet, werden sie"
  echo " durch Underline ersetzt und die Datei umbenannt."
}

load_nas_file() {
  log "ogr2ogr import Datei: ${NAS_FILE}"

  if [ $TRANSACTION = "YES" ] ; then
    ACTIVE_SCHEMA="${POSTGRES_SCHEMA}_neu"
  else
    ACTIVE_SCHEMA="${POSTGRES_SCHEMA}"
  fi

  #${OGR_BINPATH}/ogr2ogr -f "PostgreSQL" --config -nlt CONVERT_TO_LINEAR -append PG:"dbname=${POSTGRES_DBNAME} active_schema=${ACTIVE_SCHEMA} user=${POSTGRES_USER} host=pgsql port=5432 password=${POSTGRES_PASSWORD}" -a_srs EPSG:25832 -oo NAS_GFS_TEMPLATE=$GFS_TEMPLATE -oo NAS_NO_RELATION_LAYER "$NAS_FILE"
	echo ${OGR_BINPATH}/ogr2ogr -f "PostgreSQL" --config -nlt CONVERT_TO_LINEAR -append PG:"dbname=${POSTGRES_DBNAME} active_schema=${ACTIVE_SCHEMA} user=${POSTGRES_USER} host=pgsql port=5432 password=${POSTGRES_PASSWORD}" -a_srs EPSG:25832 -oo NAS_GFS_TEMPLATE=$GFS_TEMPLATE -oo NAS_NO_RELATION_LAYER "$NAS_FILE"
}

archivate_nas_file() {
  ARCHIV_DIR="${NAS_DIR}/archiv"
  mkdir -p $ARCHIV_DIR
  echo "Verschiebe Importdatei ${NAS_FILE} ins archiv ${ARCHIV_DIR}."
  mv $NAS_FILE $ARCHIV_DIR
}

#LOG_LEVEL ... 0 nicht gelogged, 1 nur auf strout, 2 nur in datei, 3 stdout und datei
log() {
  if (( $LOG_LEVEL > 1 )) ; then
    echo -e "$(date): $1" >> ${LOG_PATH}/${LOG_FILE}
  fi

  if (( $LOG_LEVEL == 1 )) || (( $LOG_LEVEL == 3 )) ; then 
    echo -e $1
  fi
}

#LOG_LEVEL ... 0 nicht gelogged, 1 nur auf strout, 2 nur in datei, 3 stdout und datei
err() {
  if (( $LOG_LEVEL > 1 )) ; then
    echo -e `date`": $1" >> ${LOG_PATH}/${ERROR_FILE}
  fi

  if (( $LOG_LEVEL == 1 )) || (( $LOG_LEVEL == 3 )) ; then 
    echo -e $1
  fi
}

###############################
# Load and set config params
SCRIPT_PATH=$(dirname $(realpath $0))
CONFIG_PATH=$(realpath ${SCRIPT_PATH}/../config)
if [ -e "${CONFIG_PATH}/config.sh" ] ; then
  source ${CONFIG_PATH}/config.sh
  log "Konfigurationsdatei: ${CONFIG_PATH}/config.sh gelesen."
	log `date`" Starte Import ALKIS-Daten mit Script import_nas.sh"
  log "Loglevel: ${LOG_LEVEL}"
  log "Reset Error Datei: ${LOG_PATH}/${ERROR_FILE}"
  echo `date` > "${LOG_PATH}/${ERROR_FILE}"
	log "Reset Log Datei: ${LOG_PATH}/${LOG_FILE}"
  echo `date` > "${LOG_PATH}/${LOG_FILE}"
else
  log "Konfigurationsdatei: ${CONFIG_PATH}/config.sh existiert nicht."
  log "Kopieren Sie ${CONFIG_PATH}/config-default.sh nach ${SCRIPT_PATH}/config/config.sh und passen die Parameter darin an."
  usage
fi

###################################################################
# Clone existing ALKIS schema with schema only and no contraints,
# indexes and default values for new nas datasets to import
if [ $TRANSACTION = "YES" ] ; then
  psql -h $POSTGRES_HOST -U $POSTGRES_USER -c "SELECT clone_schema_to_log_queries('${POSTGRES_SCHEMA}', '${POSTGRES_SCHEMA}_neu')" $POSTGRES_DBNAME
fi

if [ ! "${TEMP_PATH}" = "" ] ; then
	# Temp-Ordner leeren ! FEHLERHAFT NICHT VERWENDEN!
	#rm -R ${TEMP_PATH}/*
	echo ${TEMP_PATH}/*
fi

##############################
# NAS-Dateien abarbeiten
cd $DATA_PATH

if [ "$(ls -A $DATA_PATH)" ] ; then
  for ZIP_FILE in ${DATA_PATH}/*.zip ; do
    log "Unzip ${ZIP_FILE} ..."
    unzip $ZIP_FILE -d $TEMP_PATH
    #rm $ZIP_FILE
  done
else
  log "Dateingangsverzeichnis: ${DATA_PATH} ist leer."
fi

# find $TEMP_PATH -iname '*.xml.gz' | sort |  while read GZ_FILE ; do
  # GZ_FILE_=${GZ_FILE// /_} # ersetzt Leerzeichen durch _ in Dateiname
  # if [ ! "$GZ_FILE" = "$GZ_FILE_" ] ; then
    # log "Benenne Datei: ${GZ_FILE} um in ${GZ_FILE_}"
    # mv "$GZ_FILE" "$GZ_FILE_"
    # GZ_FILE="$GZ_FILE_"
  # fi
  # log "Extrahiere: ${GZ_FILE}"
  # gunzip $GZ_FILE
# done

error="NO"
FIRST_FILE="YES"
find ${TEMP_PATH}/NAS -iname '*.xml' | sort |  while read NAS_FILE ; do

# ToDo nicht einfach nur erstes ignorieren, sondern wenn es eine Metadatei ist.
# Nimm erstmal immer alle
#  if [ -z $FIRST_FILE ] ; then
    log "Ausgewählte Datei ${NAS_FILE}."
    NAS_FILE_=${NAS_FILE// /_} # ersetzt Leerzeichen durch _ in Dateiname
    if [ ! "$NAS_FILE" = "$NAS_FILE_" ] ; then
      echo "Benenne Datei ${NAS_FILE} um in ${NAS_FILE_}"
      mv $NAS_FILE $NAS_FILE_
      $NAS_FILE=$NAS_FILE_
    fi
    NAS_DIR=$(dirname "${NAS_FILE}")

    load_nas_file

    if [ -n "$(grep 'ERROR' ${LOG_PATH}/${ERROR_FILE}" ] ; then
      err "Fehler beim Einlesen der Datei: ${NAS_FILE}."
      error="YES"
      echo tail -n100 ${LOG_PATH}/${ERROR_FILE}
      break
    else
      # log success
      # and optional cp IMPORT_FILE to archive
      archivate_nas_file
      rm IMPORT_FILE
    fi
#  else
#    echo "Ignore file ${NAS_FILE} ..."
#    FIRST_FILE=""
#  fi;
done

if [ $error = "NO"] ; then
  # ogr2ogr read all xml files successfully

  if [ $TRANSACTION = "YES" ] ; then
    # copy data from alkis import schema to original schema
    psql -h $POSTGRES_HOST -U $POSTGRES_USER -c "SELECT exec_sql(query) FROM ${POSTGRES_SCHEMA}_neu.queries" $POSTGRES_DBNAME
  fi
  
fi
