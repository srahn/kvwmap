BEGIN;

INSERT INTO alkis.aa_anlassart (
	id,
	value
)
SELECT
	'300500',
	'Veränderung der Geometrie auf Grund der Homogenisierung'
FROM
	alkis.aa_anlassart
WHERE
	NOT EXISTS (
		SELECT
			NULL
		FROM
			alkis.aa_anlassart
		WHERE
			id = '300500'
	)
LIMIT 1;

COMMIT;
