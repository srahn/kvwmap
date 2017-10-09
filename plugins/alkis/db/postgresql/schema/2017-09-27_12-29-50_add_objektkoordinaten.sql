BEGIN;

SELECT AddGeometryColumn('alkis','ax_historischesflurstueckalb', 'objektkoordinaten', :alkis_epsg, 'POINT', 2);
CREATE INDEX ax_historischesflurstueckalb_objektkoordinaten_idx ON alkis.ax_historischesflurstueckalb USING gist (objektkoordinaten);
COMMENT ON COLUMN alkis.ax_historischesflurstueckalb.objektkoordinaten IS 'objektkoordinaten  GM_Point 0..1';

SELECT AddGeometryColumn('alkis','ax_historischesflurstueckohneraumbezug', 'objektkoordinaten', :alkis_epsg, 'POINT', 2);
CREATE INDEX ax_historischesflurstueckohneraumbezug_objektkoordinate1 ON alkis.ax_historischesflurstueckohneraumbezug USING gist (objektkoordinaten);
COMMENT ON COLUMN alkis.ax_historischesflurstueckohneraumbezug.objektkoordinaten IS 'objektkoordinaten  GM_Point 0..1';

SELECT AddGeometryColumn('alkis','ax_historischesflurstueck', 'objektkoordinaten', :alkis_epsg, 'POINT', 2);
CREATE INDEX ax_historischesflurstueck_objektkoordinaten_idx ON alkis.ax_historischesflurstueck USING gist (objektkoordinaten);
COMMENT ON COLUMN alkis.ax_historischesflurstueck.objektkoordinaten IS 'objektkoordinaten  GM_Point 0..1';

SELECT AddGeometryColumn('alkis','ax_flurstueck', 'objektkoordinaten', :alkis_epsg, 'POINT', 2);
CREATE INDEX ax_flurstueck_objektkoordinaten_idx ON alkis.ax_flurstueck USING gist (objektkoordinaten);
COMMENT ON COLUMN alkis.ax_flurstueck.objektkoordinaten IS 'objektkoordinaten  GM_Point 0..1';

COMMIT;
