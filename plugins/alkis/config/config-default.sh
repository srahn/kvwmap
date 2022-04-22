#!/bin/bash

POSTGRES_SCHEMA="alkis"
GFS_TEMPLATE="../config/alkis-schema.gfs"
DATEN_ORDNER="ff"
PGSQL_CONTAINER="kvwmap_prod_pgsql"
GDAL_CONTAINER="kvwmap_prod_gdal_1"
LOG_FILE="import.log"
ERROR_FILE="error.log"
declare -i LOG_LEVEL=1 # 0 nicht gelogged, 1 nur auf stdout, 2 nur in datei, 3 stdout und datei

UNZIP_PASSWORD="secret2"

EPSG_CODE="25833"