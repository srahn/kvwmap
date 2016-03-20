#!/bin/bash

smtp_server='smtp.p4.net'
smtp_port='25'
mail_queue_path=/var/docker/www/tmp/mail_queue
mail_archiv_path=/var/docker/www/tmp/mail_archiv

mkdir -p $mail_archiv_path

file=`find $mail_queue_path -name "email*" | head -n 1`

while [ ! -z $file -a -e $file ]
do
  echo "file: $file exists."
  to_email=`cat $file | jq -r '.to_email'`
  from_email=`cat $file | jq -r '.from_email'`
  subject=`cat $file | jq -r '.subject'`
  message=`cat $file | jq -r '.message'`
  attachment=`cat $file | jq -r '.attachment'`

  echo "sendEmail -v -t $to_email -f $from_email -s ${smtp_server}:${smtp_port} -o tls=yes -u ${subject} -m ${message} -a $attachment"
  sendEmail -v -t $to_email -f $from_email -s ${smtp_server}:${smtp_port} -o tls=yes -u "${subject}" -m "${message}" -a $attachment

  mv $file $mail_archiv_path
  echo "mail versendet und Datei: $file ins Archiv verschoben."
  file=`find $mail_queue_path -name "email*" | head -n 1`
done

echo 'fertig'
