BEGIN;

DROP FUNCTION IF EXISTS st_area_utm(geometry, integer, numeric, integer);

CREATE OR REPLACE FUNCTION st_area_utm(ingeom geometry, srid integer, r numeric, mh integer)
  RETURNS numeric AS
$BODY$DECLARE

	-- srid : EPSG-Code des Koordinatensystems, bei dem die Flächenreduktion berücksichtigt werden soll
	-- r : Erdradius in Metern
	-- mh : Mittelwert für die Quasigeoidundulation

		area_utm numeric;
		area_loop numeric;
		em numeric; 
		i integer;
		k numeric;
		geom geometry;
		mpoint geometry;
		mgeom geometry;
		NumB integer;
		NBH integer; 
		x character varying;
		y character varying; 
  Begin

  geom = ingeom;

	IF st_srid(geom) != srid THEN
		return st_area(geom);
	ELSE
	 BEGIN
		IF (GeometryType(geom) != 'POLYGON' AND GeometryType(geom) != 'MULTIPOLYGON' AND GeometryType(geom) != 'GEOMETRYCOLLECTION') THEN 
			return 0;
		END IF;

		IF (GeometryType(geom) = 'GEOMETRYCOLLECTION') THEN 
			geom = ST_CollectionExtract(geom, 3);
		END IF;

		IF (GeometryType(geom) = 'POLYGON') THEN 
			BEGIN 
				em=0;

				mpoint = st_centroid(geom);
				x = st_x(mpoint);
				y = st_y(mpoint);
				em = x;

				NumB='33'||substr(x,1,1)||substr(y,1,1)||substr(y,2,1)||substr(x,2,1)||substr(x,3,1)||substr(y,3,1)||substr(y,4,1) as integer;
				SELECT mhoehe INTO NBH FROM utm_nbh WHERE utm_nbh.nb = NumB;
				IF NBH IS NULL THEN
					return st_area(geom);
				END IF;
				NBH=mh+NBH;

				--red. UTM-Fläche berechnen
				k = (1 - (NBH / r)) * (1 + (((em - 500000)*(em - 500000))/(2 * r * r))) * 0.9996 ;
				area_utm = st_area(geom) / (k * k);
			 
				RETURN area_utm;
			 
			END;
		END IF;

		IF (GeometryType(geom) = 'MULTIPOLYGON') THEN 
			BEGIN 
				i=1;  
				area_utm=0;

				-- Multipolygon in Polygone und Neuaufruf Funktion (siehe Geometrietyp Polygon) aller Einzelpolygone
				WHILE i<=ST_NumGeometries(geom) LOOP
					mgeom=st_geometryn(geom, i);
					area_loop=st_area_utm(mgeom, srid, r, mh);
					area_utm=area_utm+area_loop;
					i=i+1;    
				END LOOP;      	

				RETURN area_utm;
			 
			END;
		END IF;

		END;
	END IF;

	End;$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

COMMIT;
