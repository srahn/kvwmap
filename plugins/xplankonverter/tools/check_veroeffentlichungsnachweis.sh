#!/bin/bash

# Das Script prüft ob der Veröffentlichungsnachweis korrekt funktioniert und die E-Mails mit Meldungen fehlerfrei versendet wurden.

kvwmap_config="$(dirname $0)/../../../config.php"

smtp_server=`grep "define('MAILSMTPSERVER'" ${kvwmap_config} | awk -F "'" '{print $4}'`
smtp_port=`grep "define('MAILSMTPPORT'" ${kvwmap_config} | awk -F " " '{print $2}' | awk -F "\)" '{print $1}'`
mail_queue_path=`grep "define('MAILQUEUEPATH'" ${kvwmap_config} | awk -F "'" '{print $4}'`
mail_archiv_path=`grep "define('MAILARCHIVPATH'" ${kvwmap_config} | awk -F "'" '{print $4}'`
mail_smtp_user=`grep "define('MAILSMTPUSER'" ${kvwmap_config} | awk -F "'" '{print $4}'`
mail_smtp_password=`grep "define('MAILSMTPPASSWORD'" ${kvwmap_config} | awk -F "'" '{print $4}'`
mail_copy_attachment=`grep "define('MAILCOPYATTACHMENT'" ${kvwmap_config} | awk -F "'" '{print $4}'`

export TZ=Europe/Berlin
result=0

# Ermittlung ob es Prüfungen für offene Auslegungen gibt, die länger als 1 Stunde zurückliegen.
# Einträge in Tabelle xplankonverter.veroeffentlichungsprotokoll Spalte last_pruefung mit observationend NOT NULL
sql="SET timezone = 'Europe/Berlin';SELECT count(*) FROM xplankonverter.veroeffentlichungsprotokolle WHERE observationend IS NULL AND last_pruefung < (date_trunc('hour', now()) - interval '1 hour')"
count=$(psql -h pgsql -U kvwmap -d kvwmapsp -t -A -c "$sql")
if [ "$count" -gt 0 ]; then
  result=$((result+=1))
fi

# Ermittlung ob es E-Mails gibt mit Fehlermeldungen
if grep -rq "ERROR =>" $mail_archiv_path; then
  result=$((result+=2))
fi

echo "$result"