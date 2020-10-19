<?php
  $output = '';
	$command = "docker exec pgsql-server psql -h localhost -p 15432 -U kvwmap -l | grep kvwmapsp | wc -l";
	exec($command, $output);
	if ($output[0] > 0) {
		echo 1;
	}
	else {
		echo 0;
	}
?>