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

rename_nas_file() {
  log "Führe Umbenennungen in ${NAS_FILE} aus."
  ruby $RENAME_SCRIPT $NAS_FILE $NAS_RENAMED_FILE
}

load_nas_file() {
  log "Kopiere $CONFIG_PATH/alkis_schema.gfs nach $GFS_FILE"
  cp $CONFIG_PATH/alkis_schema.gfs $GFS_FILE

  log "ogr2ogr import Datei: ${IMPORT_FILE}"
  ${OGR_BINPATH}/ogr2ogr -f "PostgreSQL" --config -nlt CONVERT_TO_LINEAR -append PG:"dbname=${POSTGRES_DBNAME} active_schema=${POSTGRES_SCHEMA} user=${POSTGRES_USER} host=pgsql port=5432 password=${POSTGRES_PASSWORD}" -a_srs EPSG:25832 "$IMPORT_FILE"
#  /usr/local/gdal/bin/ogr2ogr -f "PostgreSQL" --config PG_USE_COPY NO -nlt CONVERT_TO_LINEAR -append PG:"dbname=${POSTGRES_DBNAME} active_schema=${POSTGRES_SCHEMA} user=${POSTGRES_USER} host=pgsql port=5432 password=${POSTGRES_PASSWORD}" /var/www/data/alkis/ff/temp/NBA_testpostgis/NBA_testpostgis3_160907_02von24_renamed.xml
}

delete_renamed_file() {
if [ $RENAME = "YES" ] ; then
#  echo "Lösche Datei mit umbenannten Tags."
  rm $NAS_RENAMED_FILE
fi
}

delete_gfs_file() {
  IMPORT_FILENAME=${IMPORT_FILE##*/}
  #echo 'import_filename: '$IMPORT_FILENAME
  IMPORT_BASENAME=${IMPORT_FILENAME%.*}
  #echo 'base: '$IMPORT_BASENAME
  GFS_FILE="${NAS_DIR}/${IMPORT_BASENAME}.gfs"
#  echo "Lösche gfs Datei ${GFS_FILE}."
  rm $GFS_FILE
}

archivate_nas_file() {
  ARCHIV_DIR="${NAS_DIR}/archiv"
  mkdir -p $ARCHIV_DIR
  echo "Verschiebe Importdatei ${IMPORT_FILE} ins archiv ${ARCHIV_DIR}."
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
  log "Konfigurationsdatei: ${ALKIS_PLUGIN_PATH}/config/config.sh gelesen."
  log "Loglevel: ${LOG_LEVEL}"
  log "Reset Error Datei: ${LOG_PATH}/${ERROR_FILE}"
  echo `date` > "${LOG_PATH}/${ERROR_FILE}"
else
  log "Konfigurationsdatei: ${CONFIG_PATH}/config.sh existiert nicht."
  log "Kopieren Sie ${CONFIG_PATH}/config-sample.sh nach ${SCRIPT_PATH}/config/config.sh und passen die Parameter darin an."
  usage
fi

##############################
# NAS-Dateien abarbeiten
cd $DATA_PATH

if [ "$(ls -A $DATA_PATH)" ] ; then
  for ZIP_FILE in ${DATA_PATH}/*.zip ; do
    log "Unzip ${ZIP_FILE} ..."
    unzip $ZIP_FILE -d $TEMP_PATH
    rm $ZIP_FILE
  done
else
  log "Dateingangsverzeichnis: ${DATA_PATH} ist leer."
fi

find $TEMP_PATH -iname '*.xml.gz' | sort |  while read GZ_FILE ; do
  GZ_FILE_=${GZ_FILE// /_} # ersetzt Leerzeichen durch _ in Dateiname
  if [ ! "$GZ_FILE" = "$GZ_FILE_" ] ; then
    log "Benenne Datei: ${GZ_FILE} um in ${GZ_FILE_}"
    mv "$GZ_FILE" "$GZ_FILE_"
    GZ_FILE="$GZ_FILE_"
  fi
  log "Extrahiere: ${GZ_FILE}"
  gunzip $GZ_FILE
done

FIRST_FILE="YES"
find $TEMP_PATH -iname '*.xml' | sort |  while read NAS_FILE ; do

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
    NAS_FILENAME=${NAS_FILE##*/}
    NAS_BASENAME=${NAS_FILENAME%.*}
    NAS_EXTENSION=${NAS_FILE##*.}
    NAS_RENAMED_FILE="${NAS_DIR}/${NAS_BASENAME}_renamed.${NAS_EXTENSION}"

    # Umbenennung der Tags
    if [ $RENAME = "YES" ] ; then
      # ToDo, führe Umbenennungen nur in files ohne _renamed am Ende aus
      rename_nas_file
      IMPORT_FILE=$NAS_RENAMED_FILE
      rm $NAS_FILE
    else
      IMPORT_FILE=$NAS_FILE
    fi

    # Einlesen mit ogr2ogr
    IMPORT_FILENAME=${IMPORT_FILE##*/}
    IMPORT_BASENAME=${IMPORT_FILENAME%.*}
    GFS_FILE="${NAS_DIR}/${IMPORT_BASENAME}.gfs"

    load_nas_file

    # ToDo
    if [ $success ] ; then
      # log success
      # and optional cp IMPORT_FILE to archive
      archivate_nas_file
      rm IMPORT_FILE
      rm GFS_FILE
    #else
      # log error
      
    fi

#  else
#    echo "Ignore file ${NAS_FILE} ..."
#    FIRST_FILE=""
#  fi;
done

commend() {
if [ -n "$(grep 'ERROR' ${ERROR_FILE})" ] ; then
  err "Fehler beim Einlesen der Datei: ${NAS_FILE}."
  erro
  echo tail -n100 $ERROR_FILE
  break
fi
#BASE_NAME=${NAS_FILE%.xml}
#mv ${BASE_NAME}.gfs ./archiv/${BASE_NAME}.gfs

}