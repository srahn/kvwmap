#!/bin/bash
# This script delete all gast user older than 1 day

CONFIG_FILE="$(dirname $0)/../config.php"
echo "CONFIG_FILE: $CONFIG_FILE"

LOGPATH=$(grep "LOGPATH'," $CONFIG_FILE | cut -d "'" -f 4)
echo "LOGPATH: $LOGPATH"

DEBUGFILE=$(grep "DEBUGFILE'," $CONFIG_FILE | cut -d "'" -f 4)
echo "DEBUGFILE: $DEBUGFILE"

if [ -d $LOGPATH ] && [[ "$LOGPATH" =~ /var/www/logs/ ]]; then
  find ${LOGPATH} -mindepth 1 -name "*.${DEBUGFILE##*.}" -type f -delete
fi
