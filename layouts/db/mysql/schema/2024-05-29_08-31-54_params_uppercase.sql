BEGIN;

	UPDATE `layer` SET `pfad` = REPLACE(`pfad`, '$current_date', '$CURRENT_DATE');
	UPDATE `layer` SET `pfad` = REPLACE(`pfad`, '$current_timestamp', '$CURRENT_TIMESTAMP');
	UPDATE `layer` SET `pfad` = REPLACE(`pfad`, '$user_id', '$USER_ID');
	UPDATE `layer` SET `pfad` = REPLACE(`pfad`, '$userid', '$USER_ID');
	UPDATE `layer` SET `pfad` = REPLACE(`pfad`, '$stelle_id', '$STELLE_ID');
	UPDATE `layer` SET `pfad` = REPLACE(`pfad`, '$stelleid', '$STELLE_ID');
	UPDATE `layer` SET `pfad` = REPLACE(`pfad`, '$hist_timestamp', '$HIST_TIMESTAMP');
	UPDATE `layer` SET `pfad` = REPLACE(`pfad`, '$language', '$LANGUAGE');
	UPDATE `layer` SET `pfad` = REPLACE(`pfad`, '$scale', '$SCALE');
	
	UPDATE `layer` SET `Data` = REPLACE(`Data`, '$current_date', '$CURRENT_DATE');
	UPDATE `layer` SET `Data` = REPLACE(`Data`, '$current_timestamp', '$CURRENT_TIMESTAMP');
	UPDATE `layer` SET `Data` = REPLACE(`Data`, '$user_id', '$USER_ID');
	UPDATE `layer` SET `Data` = REPLACE(`Data`, '$userid', '$USER_ID');
	UPDATE `layer` SET `Data` = REPLACE(`Data`, '$stelle_id', '$STELLE_ID');
	UPDATE `layer` SET `Data` = REPLACE(`Data`, '$stelleid', '$STELLE_ID');
	UPDATE `layer` SET `Data` = REPLACE(`Data`, '$hist_timestamp', '$HIST_TIMESTAMP');
	UPDATE `layer` SET `Data` = REPLACE(`Data`, '$language', '$LANGUAGE');
	UPDATE `layer` SET `Data` = REPLACE(`Data`, '$scale', '$SCALE');
	
	UPDATE `layer_attributes` SET `options` = REPLACE(`options`, '$current_date', '$CURRENT_DATE') WHERE `form_element_type` != 'dynamicLink';
	UPDATE `layer_attributes` SET `options` = REPLACE(`options`, '$current_timestamp', '$CURRENT_TIMESTAMP') WHERE `form_element_type` != 'dynamicLink';
	UPDATE `layer_attributes` SET `options` = REPLACE(`options`, '$user_id', '$USER_ID') WHERE `form_element_type` != 'dynamicLink';
	UPDATE `layer_attributes` SET `options` = REPLACE(`options`, '$userid', '$USER_ID') WHERE `form_element_type` != 'dynamicLink';
	UPDATE `layer_attributes` SET `options` = REPLACE(`options`, '$stelle_id', '$STELLE_ID') WHERE `form_element_type` != 'dynamicLink';
	UPDATE `layer_attributes` SET `options` = REPLACE(`options`, '$stelleid', '$STELLE_ID') WHERE `form_element_type` != 'dynamicLink';
	UPDATE `layer_attributes` SET `options` = REPLACE(`options`, '$hist_timestamp', '$HIST_TIMESTAMP') WHERE `form_element_type` != 'dynamicLink';
	UPDATE `layer_attributes` SET `options` = REPLACE(`options`, '$language', '$LANGUAGE') WHERE `form_element_type` != 'dynamicLink';
	UPDATE `layer_attributes` SET `options` = REPLACE(`options`, '$scale', '$SCALE') WHERE `form_element_type` != 'dynamicLink';
	
	UPDATE `layer_attributes` SET `default` = REPLACE(`default`, '$current_date', '$CURRENT_DATE');
	UPDATE `layer_attributes` SET `default` = REPLACE(`default`, '$current_timestamp', '$CURRENT_TIMESTAMP');
	UPDATE `layer_attributes` SET `default` = REPLACE(`default`, '$user_id', '$USER_ID');
	UPDATE `layer_attributes` SET `default` = REPLACE(`default`, '$userid', '$USER_ID');
	UPDATE `layer_attributes` SET `default` = REPLACE(`default`, '$stelle_id', '$STELLE_ID');
	UPDATE `layer_attributes` SET `default` = REPLACE(`default`, '$stelleid', '$STELLE_ID');
	UPDATE `layer_attributes` SET `default` = REPLACE(`default`, '$hist_timestamp', '$HIST_TIMESTAMP');
	UPDATE `layer_attributes` SET `default` = REPLACE(`default`, '$language', '$LANGUAGE');
	UPDATE `layer_attributes` SET `default` = REPLACE(`default`, '$scale', '$SCALE');

COMMIT;
