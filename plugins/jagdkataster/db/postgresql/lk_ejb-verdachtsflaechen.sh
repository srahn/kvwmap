#!/bin/sh

# Füllt die Tabelle ejb_verdachtsflaechen
# mit aggregierten Flächen > 75 ha aller Eigentümer im ganzen Landkreis
# mit Puffer 20 Meter


### setzt Variablen ###
DATUM=`date +%a`; export DATUM
PSQLPATH=/usr/lib/postgresql/9.1/bin; export PSQLPATH
PGUSERNAME=kvwmap; export PGUSERNAME
PGDBNAME=kvwmapsp; export PGDBNAME
SCHEMANAME=jagdkataster; export SCHEMANAME
TABLENAME=lk_ejb_verdachtsflaechen; export TABLENAME
LOGFILE=/pfad/zu/logs/lk_ejb_verdachtsflaechen.log; export LOGFILE


echo " " >> $LOGFILE 2>&1
echo "############## Beginn `date +%c` ################" >> $LOGFILE 2>&1
echo " " >> $LOGFILE 2>&1


$PSQLPATH/psql -U $PGUSERNAME -d $PGDBNAME -c "
BEGIN;
DROP INDEX $SCHEMANAME.ixlkvrejbverd_the_geom_gist;
TRUNCATE $SCHEMANAME.$TABLENAME;
INSERT INTO $SCHEMANAME.$TABLENAME 
select eigentuemer, round(st_area((the_geom).geom)), (the_geom).geom from (
select 
 eigentuemer, 
st_dump(st_buffer(st_union(the_geom), -10)) as the_geom 
from (
  select
   st_buffer(f.wkb_geometry,10) as the_geom, 
   array_to_string(array(
   select 
      p.nachnameoderfirma||CASE WHEN p.vorname IS Null then '' ELSE ', '||p.vorname END||
      CASE WHEN p.namensbestandteil IS Null then '' ELSE ', '||p.namensbestandteil END
      from 
      alkis.ax_buchungsstelle sa
      LEFT JOIN alkis.ax_buchungsblatt g ON g.gml_id::text = sa.istbestandteilvon::text
      LEFT JOIN alkis.ax_namensnummer n ON n.istbestandteilvon::text = g.gml_id::text
      LEFT JOIN alkis.ax_person p ON p.gml_id::text = n.benennt::text
      WHERE g.bezirk = gg.bezirk AND g.buchungsblattnummermitbuchstabenerweiterung=gg.buchungsblattnummermitbuchstabenerweiterung 
      AND sa.endet IS NULL 
      AND g.endet IS NULL 
      AND n.endet IS NULL 
      AND p.endet IS NULL
      AND (n.benennt is not null OR n.benennt != '')
      GROUP BY p.nachnameoderfirma, p.vorname, p.namensbestandteil
      ORDER BY p.nachnameoderfirma, p.vorname, p.namensbestandteil
     ),' || '
    ) as eigentuemer 
    FROM alkis.ax_flurstueck f, alkis.ax_buchungsstelle s, alkis.ax_buchungsblatt gg
    WHERE s.gml_id::text = f.istgebucht::text
    AND gg.gml_id::text = s.istbestandteilvon::text
    AND f.endet IS NULL AND s.endet IS NULL AND gg.endet IS NULL
    GROUP BY  f.wkb_geometry, gg.bezirk, gg.buchungsblattnummermitbuchstabenerweiterung
 ) as foo
group by eigentuemer
) as foofoo 
where st_area((the_geom).geom)>750000
;
CREATE INDEX ixlkvrejbverd_the_geom_gist
 ON $SCHEMANAME.$TABLENAME
 USING gist
 (the_geom );
END;

"  # Ende SQL

if test $? -eq 0
   then
     echo "   Verdachtsflächen erfolgreich erneuert" >> $LOGFILE 2>&1
   else
     echo ">> Verdachtsflächen konnten nicht erneuert werden" >> $LOGFILE 2>&1
fi

# Tabelle analysieren
    $PSQLPATH/psql -U $PGUSERNAME -d $PGDBNAME -c "
    
       VACUUM ANALYZE $SCHEMANAME.$TABLENAME;
       
    "  # Ende SQL
    
    if test $? -eq 0
      then
        echo "   VACUUM erfolgreich durchgeführt" >> $LOGFILE 2>&1
      else
        echo ">> VACUUM konnte nicht durchgeführt werden" >> $LOGFILE 2>&1
    fi
    
   
echo " " >> $LOGFILE 2>&1
echo "############## Ende `date +%c` ################" >> $LOGFILE 2>&1
echo " " >> $LOGFILE 2>&1

exit 0