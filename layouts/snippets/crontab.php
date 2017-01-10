<h2 style="margin-top: 20px;">crontab</h2>
<div style="
	color: white;
	background-color: #000;
	border: 1px solid #fff;
	text-align: left;
	margin-top: 5px;
	margin-left: 20px;
	margin-right: 20px;
	margin-bottom: 30px;
	padding: 5px;
	border-radius: 0px
"><?php
	echo implode('<br>', array_map(
		function($crontab) {
			if (!empty($crontab->get('time')) and !empty($crontab->get('query'))) {
				$line = $crontab->get('time') . ' gisadmin PGPASSWORD=' . $this->pgdatabase->passwd . ' psql -h pgsql -U ' . $this->pgdatabase->user . ' -c "' . $crontab->get('query') . '" ' . $this->pgdatabase->dbName;
			}
			else {
				$line = '';
			}
			return $line;
		},
		$this->cronjobs
	)); ?>
</div>
<p>
<a class="btn btn-new" href="index.php?go=cronjobs_anzeigen"><i class="fa fa-clock-o" style="color: white;"></i> Cronjobs Anzeigen</a>