BEGIN;

SELECT AddGeometryColumn('ax_historischesflurstueckalb', 'objektkoordinaten', 25833, 'POINT', 2);
CREATE INDEX ax_historischesflurstueckalb_objektkoordinaten_idx ON ax_historischesflurstueckalb USING gist (objektkoordinaten);
COMMENT ON COLUMN ax_historischesflurstueckalb.objektkoordinaten IS 'objektkoordinaten  GM_Point 0..1';

SELECT AddGeometryColumn('ax_historischesflurstueckohneraumbezug', 'objektkoordinaten', 25833, 'POINT', 2);
CREATE INDEX ax_historischesflurstueckohneraumbezug_objektkoordinate1 ON ax_historischesflurstueckohneraumbezug USING gist (objektkoordinaten);
COMMENT ON COLUMN ax_historischesflurstueckohneraumbezug.objektkoordinaten IS 'objektkoordinaten  GM_Point 0..1';

SELECT AddGeometryColumn('ax_historischesflurstueck', 'objektkoordinaten', 25833, 'POINT', 2);
CREATE INDEX ax_historischesflurstueck_objektkoordinaten_idx ON ax_historischesflurstueck USING gist (objektkoordinaten);
COMMENT ON COLUMN ax_historischesflurstueck.objektkoordinaten IS 'objektkoordinaten  GM_Point 0..1';

SELECT AddGeometryColumn('ax_flurstueck', 'objektkoordinaten', 25833, 'POINT', 2);
CREATE INDEX ax_flurstueck_objektkoordinaten_idx ON ax_flurstueck USING gist (objektkoordinaten);
COMMENT ON COLUMN ax_flurstueck.objektkoordinaten IS 'objektkoordinaten  GM_Point 0..1';

COMMIT;
