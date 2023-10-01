<?
	if (isset($this) AND is_object($this) AND get_class($this) == 'administration') {
		$chart_dir = WWWROOT . APPLVERSION . THIRDPARTY_PATH . 'Chart';
		if (!file_exists($chart_dir)) {
			mkdir($chart_dir, 0770);
		}
		if (!file_exists($chart_dir . 'chart.js')) {
			$content = file_get_contents('https://dev.gdi-service.de/3rdparty/Chart/chart.js');
			file_put_contents(WWWROOT . APPLVERSION . THIRDPARTY_PATH . 'Chart/chart.js', $content);
		}
	}
?>