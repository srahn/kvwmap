#!/bin/bash

#############
# functions

convert_nas_file() {
  log "ogr2ogr konvertiert Datei: ${NAS_FILE}"

  ${OGR_BINPATH}/ogr2ogr -f PGDump -append -a_srs EPSG:${EPSG_CODE} -nlt CONVERT_TO_LINEAR -lco SCHEMA=${POSTGRES_SCHEMA} -lco CREATE_SCHEMA=OFF -lco CREATE_TABLE=OFF --config PG_USE_COPY YES --config NAS_GFS_TEMPLATE $GFS_TEMPLATE --config NAS_NO_RELATION_LAYER YES ${SQL_FILE} ${NAS_FILE}
	#echo ${OGR_BINPATH}/ogr2ogr -f PGDump -append -a_srs EPSG:${EPSG_CODE} -nlt CONVERT_TO_LINEAR -lco SCHEMA=${POSTGRES_SCHEMA} -lco CREATE_SCHEMA=OFF -lco CREATE_TABLE=OFF --config PG_USE_COPY YES --config NAS_GFS_TEMPLATE "../config/alkis-schema.gfs" --config NAS_NO_RELATION_LAYER YES ${SQL_FILE} ${NAS_FILE}
	
	#/usr/local/gdal/bin/ogr2ogr -f PGDump -append -a_srs EPSG:25833 -nlt CONVERT_TO_LINEAR -lco SCHEMA=alkis -lco CREATE_SCHEMA=OFF -lco CREATE_TABLE=OFF --config PG_USE_COPY YES --config NAS_GFS_TEMPLATE "../config/alkis-schema.gfs" --config NAS_NO_RELATION_LAYER YES /var/www/data/alkis/ff/import/NAS/nba_landmv_lro_160112_0342von2024_306000_5948000.sql /var/www/data/alkis/ff/import/NAS/nba_landmv_lro_160112_0342von2024_306000_5948000.xml
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
fi

if [ ! ${IMPORT_PATH} = "" ] ; then
	# Temp-Ordner leeren
	rm -R ${IMPORT_PATH}/*
fi

##############################
# NAS-Dateien abarbeiten
cd $DATA_PATH

if [ "$(ls -A $DATA_PATH)" ] ; then
  for ZIP_FILE in ${DATA_PATH}/*.zip ; do
    log "Unzip ${ZIP_FILE} ..."
    unzip $ZIP_FILE -d $IMPORT_PATH
    #rm $ZIP_FILE
  done
else
  log "Dateieingangsverzeichnis: ${DATA_PATH} ist leer."
fi

error="NO"
FIRST_FILE="YES"
find ${IMPORT_PATH}/NAS -iname '*.xml' | sort |  while read NAS_FILE ; do
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
	SQL_FILE="${NAS_DIR}/${NAS_BASENAME}.sql"

	convert_nas_file

	if [ -n "$(grep 'ERROR' ${LOG_PATH}/${ERROR_FILE})" ] ; then
		err "Fehler beim Einlesen der Datei: ${NAS_FILE}."
		error="YES"
		echo tail -n100 ${LOG_PATH}/${ERROR_FILE}
		break
	fi
done

if [ $error = "NO" ] ; then
  # ogr2ogr read all xml files successfully
	# execute transaction sql file
	psql -h $POSTGRES_HOST -U $POSTGRES_USER -f ... $POSTGRES_DBNAME
fi