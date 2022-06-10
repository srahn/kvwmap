<h2 style="margin-top: 20px;">crontab gisadmin</h2>
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
	border-radius: 0px;
	max-width: 800px;
"><?
	echo implode("<br>", $this->crontab_lines['gisadmin']); ?>
</div>
<p>
<h2 style="margin-top: 20px;">crontab root</h2>
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
	border-radius: 0px;
	max-width: 800px;
"><?
	echo implode("<br>", $this->crontab_lines['root']); ?>
</div>
<a class="btn btn-new" href="index.php?go=cronjobs_anzeigen&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i class="fa fa-clock-o" style="color: white;"></i> Cronjobs Anzeigen</a>