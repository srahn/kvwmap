BEGIN;

	UPDATE `layer_parameter` SET `default_value` = REPLACE(`default_value`, '$user_id', '$USER_ID');
	UPDATE `layer_parameter` SET `default_value` = REPLACE(`default_value`, '$stelle_id', '$STELLE_ID');
	
	UPDATE `layer_parameter` SET `options_sql` = REPLACE(`options_sql`, '$user_id', '$USER_ID');
	UPDATE `layer_parameter` SET `options_sql` = REPLACE(`options_sql`, '$stelle_id', '$STELLE_ID');
	
	UPDATE `used_layer` SET `Filter` = REPLACE(`Filter`, '$userid', '$USER_ID');
	
	UPDATE `u_attributfilter2used_layer` SET `attributvalue` = REPLACE(`attributvalue`, '$userid', '$USER_ID');
	
	UPDATE `u_menues` SET `links` = REPLACE(`links`, '$current_date', '$CURRENT_DATE');
	UPDATE `u_menues` SET `links` = REPLACE(`links`, '$current_timestamp', '$CURRENT_TIMESTAMP');
	UPDATE `u_menues` SET `links` = REPLACE(`links`, '$user_id', '$USER_ID');
	UPDATE `u_menues` SET `links` = REPLACE(`links`, '$userid', '$USER_ID');
	UPDATE `u_menues` SET `links` = REPLACE(`links`, '$stelle_id', '$STELLE_ID');
	UPDATE `u_menues` SET `links` = REPLACE(`links`, '$stelleid', '$STELLE_ID');
	UPDATE `u_menues` SET `links` = REPLACE(`links`, '$hist_timestamp', '$HIST_TIMESTAMP');
	UPDATE `u_menues` SET `links` = REPLACE(`links`, '$language', '$LANGUAGE');
	UPDATE `u_menues` SET `links` = REPLACE(`links`, '$scale', '$SCALE');
	
	UPDATE `u_menues` SET `onclick` = REPLACE(`onclick`, '$current_date', '$CURRENT_DATE');
	UPDATE `u_menues` SET `onclick` = REPLACE(`onclick`, '$current_timestamp', '$CURRENT_TIMESTAMP');
	UPDATE `u_menues` SET `onclick` = REPLACE(`onclick`, '$user_id', '$USER_ID');
	UPDATE `u_menues` SET `onclick` = REPLACE(`onclick`, '$userid', '$USER_ID');
	UPDATE `u_menues` SET `onclick` = REPLACE(`onclick`, '$stelle_id', '$STELLE_ID');
	UPDATE `u_menues` SET `onclick` = REPLACE(`onclick`, '$stelleid', '$STELLE_ID');
	UPDATE `u_menues` SET `onclick` = REPLACE(`onclick`, '$hist_timestamp', '$HIST_TIMESTAMP');
	UPDATE `u_menues` SET `onclick` = REPLACE(`onclick`, '$language', '$LANGUAGE');
	UPDATE `u_menues` SET `onclick` = REPLACE(`onclick`, '$scale', '$SCALE');

COMMIT;
