#!/bin/bash

smtp_server=`grep "define('MAILSMTPSERVER'" config.php | awk -F "'" '{print $4}'`
smtp_port=`grep "define('MAILSMTPPORT'" config.php | awk -F "'" '{print $4}'`
mail_queue_path=`grep "define('MAILQUEUEPATH'" config.php | awk -F "'" '{print $4}'`
mail_archiv_path=`grep "define('MAILARCHIVPATH'" config.php | awk -F "'" '{print $4}'`

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

  if [[ -z $attachement ]]; then
    echo "sendEmail -v -t $to_email -f $from_email -s ${smtp_server}:${smtp_port} -o tls=yes -u ${subject} -m ${message}"
    sendEmail -v -t $to_email -f $from_email -s ${smtp_server}:${smtp_port} -o tls=yes -u "${subject}" -m "${message}"
  else
    echo "sendEmail -v -t $to_email -f $from_email -s ${smtp_server}:${smtp_port} -o tls=yes -u ${subject} -m ${message} -a $attachment"
    sendEmail -v -t $to_email -f $from_email -s ${smtp_server}:${smtp_port} -o tls=yes -u "${subject}" -m "${message}" -a $attachment
  fi

  mv $file $mail_archiv_path
  mv $attachment $mail_archiv_path
  echo "mail versendet und Datei: $file ins Archiv verschoben."
  file=`find $mail_queue_path -name "email*" | head -n 1`
done

echo 'fertig'
