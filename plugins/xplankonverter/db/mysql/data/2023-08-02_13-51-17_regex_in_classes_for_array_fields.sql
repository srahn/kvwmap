BEGIN;
UPDATE classes i
	INNER JOIN (
		SELECT l.Name AS layer_name,
			a.layer_id,
			a.name AS attribute_name,
			c.Class_ID,
			c.Name AS class_name,
			c.Expression,
			concat(
				'(''[zweckbestimmung]'' ~ ''\\b',
				replace(
					replace(
						replace(
							replace(
								replace(
									replace(
										replace(
											replace(
												replace(
													replace(
														replace(
															replace(
																replace(
																	replace(
																		replace(c.Expression, '\'[zweckbestimmung]\'', ''),
																		'=',
																		''
																	),
																	'((',
																	'('
																),
																'))',
																')'
															),
															'(',
															'{'
														),
														')',
														'}'
													),
													',',
													'\',\''
												),
												'OR',
												','
											),
											'AND',
											','
										),
										'~',
										''
									),
									' ',
									''
								),
								'{',
								''
							),
							'}',
							''
						),
						'''',
						''
					),
					',',
					'\\b|\\b'
				),
				'\\b'')'
			) new_expression
		FROM layer l
			JOIN layer_attributes a ON l.Layer_ID = a.layer_id
			JOIN classes c ON a.layer_id = c.Layer_ID
		WHERE l.Layer_ID != 5072
			AND a.name like 'zweckbestimmung'
			AND a.type like '_text'
			AND c.Expression LIKE '%zweckbestimmung%'
			AND c.Expression NOT LIKE '%rechtsstand%'
			AND c.Expression NOT LIKE '%ebene%'
			AND c.Expression NOT LIKE '%detail%'
			AND c.Expression NOT LIKE '%text%'
			AND c.Expression NOT LIKE '%!=%'
			AND c.Expression NOT LIKE '%\'\'%'
	) n ON i.Class_ID = n.Class_ID
SET i.Expression = n.new_expression;
UPDATE layer i
	INNER JOIN (
		SELECT DISTINCT a.layer_id,
			l.Name
		FROM layer l
			JOIN layer_attributes a ON l.Layer_ID = a.layer_id
			JOIN classes c ON a.layer_id = c.Layer_ID
		WHERE l.Layer_ID != 5072
			AND a.name like 'zweckbestimmung'
			AND a.type like '_text'
			AND c.Expression LIKE '%zweckbestimmung%'
			AND c.Expression NOT LIKE '%rechtsstand%'
			AND c.Expression NOT LIKE '%ebene%'
			AND c.Expression NOT LIKE '%detail%'
			AND c.Expression NOT LIKE '%text%'
			AND c.Expression NOT LIKE '%!=%'
			AND c.Expression NOT LIKE '%\'\'%'
	) n ON i.Layer_ID = n.layer_id
SET i.classitem = 'zweckbestimmung'
WHERE i.classitem != 'zweckbestimmung';

SELECT
  c.Class_ID,
  l.Name,
  c.Expression,
  replace(replace(c.Expression, '\'[sondernutzung]\' = \'', '\'[sondernutzung]\' ~ \'\\b\\b'), '\')', '\\b\\b\')')
FROM
  classes c JOIN
  layer l ON c.Layer_ID = l.Layer_ID
WHERE
  l.Name LIKE 'fp_bebauungsflaeche_polygons' AND
  c.Expression LIKE '%sondernutzung]\' =%' AND
  c.Expression NOT LIKE '%sondernutzung]\' = \'\'%';
COMMIT;