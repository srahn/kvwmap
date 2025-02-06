<?

// Sie können diese Seite nach Ihren Wünschen verändern, sollten sie dann aber nach .../snippets/custom kopieren und die Konstante LAYER_ERROR_PAGE entsprechend anpassen

$email = '';
//$subject = 'Fehler in kvwmap - Thema '.umlaute_javascript($error_layername).' kann nicht angezeigt werden';
//$body = 'Sehr geehrte Damen und Herren,%0D%0A%0D%0Adas Thema '.umlaute_javascript($error_layername).' verursacht einen Fehler und kann nicht angezeigt werden. Folgende Fehlermeldung wird ausgegeben:%0D%0A%0D%0A'.umlaute_javascript($error_details);
$subject = 'Fehler in kvwmap - Thema  kann nicht angezeigt werden';
$body = 'Sehr geehrte Damen und Herren,%0D%0A%0D%0Adas Thema verursacht einen Fehler und kann nicht angezeigt werden. Folgende Fehlermeldung wird ausgegeben:%0D%0A%0D%0A';
global $errors;
?>

<style type="text/css">
#errormeldung_wrapper {
	width:100%;
	position:relative;
	font-family: SourceSansPro1, Arial, Verdana, Helvetica,sans-serif;
	cursor: default;
	white-space: normal;
	line-height: unset;
	color: #333;
}
#errormeldung_wrapper a {
	color: #990000;
	text-decoration: none;
}
#errormeldung_header {
	height: 50px;
	background-color: #f0f0f0;
	display: flex;
	flex-wrap: nowrap;
	justify-content: flex-start;
	align-items: center;
}
.errormeldung_hoppla {
	margin-left: 1em;
}
#errormeldung_body {
	margin-left: 1em;
}
#errormeldung_body li {
	color: #333;
	padding: 0 0 1em 0;
}
.errormeldung_details li {
	background-color: #f0f0f0;	
}
.errormeldung_details li p {
	background-color: #f0f0f0;
		padding: 1em;
}
</style>

<html>
	<head>
		<title><? echo TITLE; ?></title>
	</head>
	<body>
	<div id="errormeldung_wrapper">
		<div id="errormeldung_header">
			<div class="errormeldung_hoppla"><h3>Hoppla!</h3></div>
		</div>	
		<div id="errormeldung_body">
			<div class="errormeldung_titel"><h4>Es ist leider ein Fehler aufgetreten!</h4></div>
			<div class="errormeldung_wastun">
				<p>
					<h4>Was können Sie tun?</h4>
					<ul>
						<li> Warten Sie einige Augenblicke und versuchen Sie dann, die Seite erneut zu laden. Klicken Sie dazu auf diesen Link:<br><a href="index.php">Neu laden</a></li>
						<li> Sollte diese Meldung danach immer noch kommen, geben Sie bitte der Geoadministration einen Hinweis. Sie können eine Email erstellen, indem Sie auf diesen Link klicken:<br><a href="mailto:<? echo $email; ?>?subject=<? echo $subject; ?>&body=<? echo $body; ?>">Hinweis versenden</a></li>
						<li> Damit Sie in kvwmap weiterarbeiten können, können Sie alle Themen ausschalten. Klicken Sie auf diesen Link:<br><a href="index.php?go=reset_layers">Themen ausschalten</a></li>
					</ul>
				</p>
			</div>
			<div class="errormeldung_details">
				<p>
					<a href="javascript:void(0)" onclick="document.getElementById('details').style.display=''"><img src="<? echo GRAPHICSPATH; ?>/menue_top_open.gif">Falls die Fehler-Details wichtig sind:</a>
					<div id="details" style="display:none;">
						<? foreach ($errors as $error) { ?>
						<p>
							<ul>
							<? echo '<li><p>'.$error.'</p></li>'; ?>
							</ul>
						</p>
						<? } ?>					
					</div>
				</p>
			</div>
		</div>
	</div>
	</body>
</html>

