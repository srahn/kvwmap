#!/bin/bash

kvwmap_config="$(dirname $0)/../config.php"

smtp_server=`grep "define('MAILSMTPSERVER'" ${kvwmap_config} | awk -F "'" '{print $4}'`
smtp_port=`grep "define('MAILSMTPPORT'" ${kvwmap_config} | awk -F " " '{print $2}' | awk -F "\)" '{print $1}'`
mail_queue_path=`grep "define('MAILQUEUEPATH'" ${kvwmap_config} | awk -F "'" '{print $4}'`
mail_archiv_path=`grep "define('MAILARCHIVPATH'" ${kvwmap_config} | awk -F "'" '{print $4}'`
mail_smtp_user=`grep "define('MAILSMTPUSER'" ${kvwmap_config} | awk -F "'" '{print $4}'`
mail_smtp_password=`grep "define('MAILSMTPPASSWORD'" ${kvwmap_config} | awk -F "'" '{print $4}'`
mail_copy_attachment=`grep "define('MAILCOPYATTACHMENT'" ${kvwmap_config} | awk -F "'" '{print $4}'`

# substitute location var/www/.. with home/gisadmin/www...
#echo $mail_queue_path
# mail_queue_path="${mail_queue_path/var/home\/gisadmin}"
# mail_archiv_path="${mail_archiv_path/var/home\/gisadmin}"
job_log_file="${mail_archiv_path}sendEmail.log"
echo "$(date +'%Y-%m-%d %H:%M:%S') Starte sendEmailAsync.sh" >> $job_log_file

mkdir -p $mail_archiv_path
# chown gisadmin.www-data $mail_archiv_path
# chmod g+w $mail_archiv_path

busyfile="${MAILQUEUEPATH}/busyfile"
if [ -f "${busyfile}" ]; then
  # Es gibt schon einen Prozess der die E-Mail-Dateien abarbeitet.
  echo "${busyfile} exists."
else
  # echo "Durchsuche ${mail_queue_path} nach E-Mail-Dateien."
  file=`find $mail_queue_path -name "email*" | head -n 1`

  while [ ! -z $file -a -e $file ]
  do
    logfile="${file%.txt}.log"
    to_email=`cat $file | jq -r '.to_email'`
    from_email=`cat $file | jq -r '.from_email'`
    subject=`cat $file | jq -r '.subject'`
    message=`cat $file | jq -r '.message'`
    attachment=`cat $file | jq -r '.attachment'`
    attachment=${attachement/var/home/gisadmin}

    #tls=auto will only use tls if available
    if [[ -z $attachment ]]; then
      #echo Ohne Attachement sendEmail -v -t $to_email -f $from_email -s ${smtp_server}:${smtp_port} -o tls=yes -u ${subject} -m ${message}
      sendEmail -v -t $to_email -f $from_email -s ${smtp_server}:${smtp_port} -o tls=auto -xu ${mail_smtp_user} -xp ${mail_smtp_password} -o message-charset=utf8 -u "${subject}" -m "${message}" > $logfile 2>&1
      echo "sendEmail -v -t $to_email -f $from_email -s ${smtp_server}:${smtp_port} -o tls=auto -xu ${mail_smtp_user} -xp ****** -o message-charset=utf8 -u \"${subject}\" -m \"${message}\"" >> $job_log_file
      #sendEmail -v -t 'peter.korduan@gdi-service.de' -f 'info@gdi-service.de' -s smtp.ionos.de:587 -o tls=yes -xu 'peter.korduan@gdi-backup.de' -xp '*****' -o message-charset=utf8 -u "Testkvwmap" -m "TestMessage"
    else
      #echo Mit attachement sendEmail -v -t $to_email -f $from_email -s ${smtp_server}:${smtp_port} -o tls=yes -u ${subject} -m ${message} -a $attachment
      #sendEmail -v -t $to_email -f $from_email -s ${smtp} -o tls=yes  -xu ${mail_smtp_user} -xp ${mail_smtp_password} -o message-charset=utf8 -u "TestPlandigital" -m "Testcontent" -a $attachment > /dev/null 2>&1
      sendEmail -v -t $to_email -f $from_email -s ${smtp_server}:${smtp_port} -o tls=auto -u "${subject}" -m "${message}" -xu ${mail_smtp_user} -xp ${mail_smtp_password} -o message-charset=utf8 -a $attachment > $logfile 2>&1
      echo "sendEmail -v -t $to_email -f $from_email -s ${smtp_server}:${smtp_port} -o tls=auto -xu ${mail_smtp_user} -xp ****** -o message-charset=utf8 -u \"${subject}\" -m \"${message}\" -a $attachment" >> $job_log_file

      if [[ -z $mail_copy_attachment ]]; then
          mv $attachment $mail_archiv_path
      else
        if [[ -z $attachment ]]; then
          cp $attachment $mail_archiv_path
        fi
      fi
    fi
    mv $file $mail_archiv_path
    mv $logfile $mail_archiv_path
    echo "E-Mail $file gesendet und nach $mail_archiv_path verschoben." >> $job_log_file
    file=`find $mail_queue_path -name "email*" | head -n 1` # Frage die nächste Mail-Datei aus dem Stapel ab.
  done
  # Keine Mail-Dateien mehr im Stapel, lösche busyfile
  if [ -f "${busyfile}" ]; then
    rm $busyfile
  fi
fi