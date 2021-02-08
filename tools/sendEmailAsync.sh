#!/bin/bash

kvwmap_config="$(dirname $0)/../config.php"
smtp_server=`grep "define('MAILSMTPSERVER'" ${kvwmap_config} | awk -F "'" '{print $4}'`
smtp_port=`grep "define('MAILSMTPPORT'" ${kvwmap_config} | awk -F "'" '{print $4}'`
mail_queue_path=`grep "define('MAILQUEUEPATH'" ${kvwmap_config} | awk -F "'" '{print $4}'`
mail_archiv_path=`grep "define('MAILARCHIVPATH'" ${kvwmap_config} | awk -F "'" '{print $4}'`
mail_smtp_user=`grep "define('MAILSMTPUSER'" ${kvwmap_config} | awk -F "'" '{print $4}'`
mail_smtp_password=`grep "define('MAILSMTPPASSWORD'" ${kvwmap_config} | awk -F "'" '{print $4}'`

mkdir -p $mail_archiv_path
chown gisadmin.www-data $mail_archiv_path
chmod g+w $mail_archiv_path

file=`find $mail_queue_path -name "email*" | head -n 1`

while [ ! -z $file -a -e $file ]
do
  #echo "file: $file exists."
  to_email=`cat $file | jq -r '.to_email'`
  from_email=`cat $file | jq -r '.from_email'`
  subject=`cat $file | jq -r '.subject'`
  message=`cat $file | jq -r '.message'`
  attachment=`cat $file | jq -r '.attachment'`

  if [[ -z $attachment ]]; then
    #echo Ohne Attachement sendEmail -v -t $to_email -f $from_email -s ${smtp_server}:${smtp_port} -o tls=yes -u ${subject} -m ${message}
    sendEmail -v -t $to_email -f $from_email -s ${smtp_server}:${smtp_port} -o tls=yes -xu ${mail_smtp_user} -xp ${mail_smtp_password} -o message-charset=utf8 -u "${subject}" -m "${message}" > /dev/null 2>&1
    #sendEmail -v -t 'peter.korduan@gdi-service.de' -f 'info@gdi-service.de' -s smtp.ionos.de:587 -o tls=yes -xu 'peter.korduan@gdi-backup.de' -xp '*****' -o message-charset=utf8 -u "Testkvwmap" -m "TestMessage"
  else
    #echo Mit attachement sendEmail -v -t $to_email -f $from_email -s ${smtp_server}:${smtp_port} -o tls=yes -u ${subject} -m ${message} -a $attachment
    sendEmail -v -t $to_email -f $from_email -s ${smtp_server}:${smtp_port} -o tls=yes  -xu ${mail_smtp_user} -xp ${mail_smtp_password} -o message-charset=utf8 -u "${subject}" -m "${message}" -a $attachment > /dev/null 2>&1
    mv $attachment $mail_archiv_path
  fi
 
  mv $file $mail_archiv_path
  #echo "E-Mail $file gesendet."
  file=`find $mail_queue_path -name "email*" | head -n 1`
done

#echo 'fertig'
