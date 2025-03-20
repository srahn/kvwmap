BEGIN;

CREATE OR REPLACE FUNCTION st_length_utm(
    geom geometry,
    srid integer,
    r numeric)
  RETURNS numeric AS
$BODY$DECLARE

	-- srid : EPSG-Code des Koordinatensystems, bei dem die Streckenreduktion berücksichtigt werden soll
	-- r : Erdradius in Metern

		length_utm numeric;
		length_loop numeric;
		em numeric; 
		i integer;
		k numeric;
		mgeom geometry;
		NBH double precision;  
		x_coords double precision[];
		y_coords double precision[];
		bbox geometry[];
	Begin

	IF st_srid(geom) != srid THEN
		return st_length(geom);
	ELSE
	 BEGIN
	 
		IF (GeometryType(geom) != 'LINESTRING' AND GeometryType(geom) != 'MULTILINESTRING') THEN 
			return 0;
		END IF;

		IF (GeometryType(geom) = 'LINESTRING') THEN 
			BEGIN 
				em=0;

				-- Speichern der Rechts- und Hochwerte in Arrays
				select into x_coords, y_coords
					array_agg(st_x(points)), array_agg(st_y(points))
				FROM (
					select (ST_DumpPoints(geom)).geom as points
				) as foo;

				-- BBox ermitteln
				select into bbox
					array_agg(points)
				from (
					select (ST_DumpPoints(ST_Envelope(geom))).geom as points
				) as foo;

				-- mittlerer Rechtswert
				select into em
					avg(x)
				from (
					select unnest(x_coords) as x
				) as foo;

				-- BBox-Test, ob mehr als 1 NB betroffen ist
				-- wenn nicht, werden die Koordinaten-Arrays auf den ersten Stützpunkt reduziert
				if(st_x(bbox[1])::integer/1000 = st_x(bbox[3])::integer/1000 AND st_y(bbox[1])::integer/1000 = st_y(bbox[3])::integer/1000) then
					x_coords = array[x_coords[1]];
					y_coords = array[y_coords[1]];
				END IF;

				-- Ermittlung der mittleren Höhe aus den Nummerierungsbezirken
				select into NBH
					avg(mhoehe)
				FROM (
					select distinct ON(nb) nb, mhoehe
					FROM (
						select unnest(x_coords)::text as x, unnest(y_coords)::text as y
					) as fooo, utm_nbh
					where utm_nbh.nb = ('33'||substr(x,1,1)||substr(y,1,1)||substr(y,2,1)||substr(x,2,1)||substr(x,3,1)||substr(y,3,1)||substr(y,4,1))::integer
				)as foo;
				
				IF NBH IS NULL THEN
					return st_length(geom);
				END IF;

				--red. UTM-Strecke berechnen
				k = (1 - (NBH / r)) * (1 + (((em - 500000)*(em - 500000))/(2 * r * r))) * 0.9996 ;
				length_utm = st_length(geom) / k;
			 
				RETURN length_utm;
			 
			END;
		END IF;

		IF (GeometryType(geom) = 'MULTILINESTRING') THEN 
			BEGIN 
				i=1;  
				length_utm=0;

				-- Multilinestring in Linestrings und Neuaufruf Funktion (siehe Geometrietyp Linestring) aller Einzellinestrings
				WHILE i<=ST_NumGeometries(geom) LOOP
					mgeom=st_geometryn(geom, i);
					length_loop=st_length_utm(mgeom, srid, r);
					length_utm=length_utm+length_loop;
					i=i+1;    
				END LOOP;      	

				RETURN length_utm;
			 
			END;
		END IF;

		END;
	END IF;

	End;$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

COMMIT;
