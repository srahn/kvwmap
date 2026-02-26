#!/bin/bash

POSTGRES_SCHEMA="alkis"
GFS_TEMPLATE="../config/alkis-schema.gfs"
DATEN_ORDNER="ff"
DOCKER_NETWORK_NAME=getenv('DOCKER_NETWORK_NAME');
PGSQL_CONTAINER=DOCKER_NETWORK_NAME . "_pgsql"
GDAL_CONTAINER=DOCKER_NETWORK_NAME . "_gdal"
LOG_FILE="import.log"
ERROR_FILE="error.log"
declare -i LOG_LEVEL=1 # 0 nicht gelogged, 1 nur auf stdout, 2 nur in datei, 3 stdout und datei

UNZIP_PASSWORD="secret2"

EPSG_CODE="25833"