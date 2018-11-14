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
	border-radius: 0px;
	max-width: 800px;
"><?php echo implode("<br>", $this->crontab_lines); ?></div>
<p>
<a class="btn btn-new" href="index.php?go=cronjobs_anzeigen"><i class="fa fa-clock-o" style="color: white;"></i> Cronjobs Anzeigen</a>