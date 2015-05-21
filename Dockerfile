FROM debian
MAINTAINER Peter Korduan <peter.korduan@gdi-service.de>

RUN alias ll='ls -l'
RUN alias rm='rm -i'

RUN apt-get update
RUN apt-get install -y apt-utils \
  apache2 \
  php5 \
  cgi-mapserver \
  mapserver-bin \
  php5-mapscript

EXPOSE 80
EXPOSE 443

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
