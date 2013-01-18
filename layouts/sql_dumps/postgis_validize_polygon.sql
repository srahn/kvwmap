-- Function: polyfromline(geometry)

-- DROP FUNCTION polyfromline(geometry);

CREATE OR REPLACE FUNCTION polyfromline(geometry)
  RETURNS geometry AS
$BODY$SELECT st_geomfromtext(replace(replace(replace(replace(replace(replace(replace(asText($1),'MULTILINESTRING','MULTIPOLYGON'),'LINESTRING','MULTIPOLYGON'), '((', '('), '))', ')'), '(', '((('), ')', ')))'), '))),(((', ')),(('), srid($1))$BODY$
  LANGUAGE 'sql' IMMUTABLE STRICT
  COST 100;
COMMENT ON FUNCTION polyfromline(geometry) IS 'Liefert eine MULTIPOLYGON Gemetrie von einer MULTILINESTRING oder LINESTRING Geometrie zurück';




-- Function: linefrompoly(geometry)

-- DROP FUNCTION linefrompoly(geometry);

CREATE OR REPLACE FUNCTION linefrompoly(geometry)
  RETURNS geometry AS
$BODY$SELECT 
	geomfromtext(
		replace(
			replace(
				replace(
					replace(
						replace(
							replace(
								replace(
									asText($1),'MULTIPOLYGON','MULTILINESTRING'
								),'POLYGON','LINESTRING'
							), '((', '('
						), '))', ')'
					), '(((', '(('
				), ')))', '))'
			), '))),(((', ')),(('
		), srid($1)
	)$BODY$
  LANGUAGE 'sql' IMMUTABLE STRICT
  COST 100;
COMMENT ON FUNCTION linefrompoly(geometry) IS 'Liefert eine LINESTRING Gemetrie von einer MULTIPOLYGON oder POLYGON Geometrie zurück';




-- Function: validize_polygon(geometry)

-- DROP FUNCTION validize_polygon(geometry);

CREATE OR REPLACE FUNCTION validize_polygon(geometry)
  RETURNS geometry AS
$BODY$
     DECLARE
	polygon alias for $1; 
	polygon2 text;
	linestring geometry;
	point1 geometry;
	point2 geometry;
	startx float;
	x1 float;
	y1 float;
	x2 float;
	y2 float;
	i integer := 1;
	last_ascent float;
	ret_array float[];
     BEGIN
      SELECT linefrompoly(polygon) into linestring;
      SELECT x(PointN(linestring, 1)) into startx;
      LOOP
        if i = NumPoints(linestring) then exit;	end if;
        SELECT PointN(linestring, i) into point1;
        SELECT PointN(linestring, i+1) into point2;
        SELECT x(point1) into x1;
        SELECT y(point1) into y1;
        SELECT x(point2) into x2;
        SELECT y(point2) into y2;
        if y1-y2 != 0 then
	  if @ (last_ascent - (x1-x2)/(y1-y2)) < 0.001 AND x1 != startx then
	    SELECT RemovePoint(linestring, i-1) into linestring;
	    --i := 1;
	    i := NumPoints(linestring)-1;
	  else
	    last_ascent = (x1-x2)/(y1-y2);
	  end if;
	end if;
	i:=i+1;
      END LOOP;
      SELECT polyfromline(linestring) into polygon2;
     RETURN polygon2;
     END;
   $BODY$
  LANGUAGE 'plpgsql' IMMUTABLE STRICT
  COST 100;

