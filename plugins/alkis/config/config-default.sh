#!/bin/bash
POSTGRES_HOST="pgsql"
POSTGRES_PORT="5432"
POSTGRES_USER="kvwmap"
POSTGRES_PASSWORD="secret1"
POSTGRES_DBNAME="alkis"
POSTGRES_SCHEMA="aaa_ogr"

DATA_PATH="/var/www/data/alkis/ff/eingang"
GFS_TEMPLATE="/var/www/data/alkis/NAS_Template.gfs"
ARCHIV_PATH="/var/www/data/alkis/ff/eingelesen"
TEMP_PATH="/var/www/data/alkis/ff/temp"
LOG_PATH="/var/www/data/alkis/ff/logs"
LOG_FILE="import.log"
ERROR_FILE="error.log"
declare -i LOG_LEVEL=1 # 0 nicht gelogged, 1 nur auf strout, 2 nur in datei, 3 stdout und datei

UNZIPPASSWORD="secret2"

RENAME="YES"
RENAME_SCRIPT="/var/www/apps/xmi2db/converter/rename_nas.rb"

OGR_BINPATH="/usr/local/gdal/bin" # inside the gdal container

ALKIS_PLUGIN_PATH="/var/www/apps/kvwmap_pet_dev/plugings/alkis"

EPSG_CODE="25833"