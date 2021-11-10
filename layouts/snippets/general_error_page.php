<?

// Sie können diese Seite nach Ihren Wünschen verändern, sollten sie dann aber nach .../snippets/custom kopieren und die Konstante LAYER_ERROR_PAGE entsprechend anpassen

$email = '';
//$subject = 'Fehler in kvwmap - Thema '.umlaute_javascript($error_layername).' kann nicht angezeigt werden';
//$body = 'Sehr geehrte Damen und Herren,%0D%0A%0D%0Adas Thema '.umlaute_javascript($error_layername).' verursacht einen Fehler und kann nicht angezeigt werden. Folgende Fehlermeldung wird ausgegeben:%0D%0A%0D%0A'.umlaute_javascript($error_details);
$subject = 'Fehler in kvwmap - Thema  kann nicht angezeigt werden';
$body = 'Sehr geehrte Damen und Herren,%0D%0A%0D%0Adas Thema verursacht einen Fehler und kann nicht angezeigt werden. Folgende Fehlermeldung wird ausgegeben:%0D%0A%0D%0A';
global $errors;
?>
<html>
	<head>
		<title><? echo TITLE; ?></title>
	</head>
	<body>
		<div style="width:80%;margin:10px auto;position:relative;font-family: Arial, Verdana, Helvetica,sans-serif;">
			<h3 style="background-color:#E6E6E6;padding:5px;">Hoppla!</h3>
			<div style="width:40%;float:left;">
			 <p>
				 <h4>Es ist leider ein Fehler aufgetreten!</h4>
					<div id="error_messages" style="width:100%;clear:both;">
						<? foreach ($errors as $error) { ?>
						<p>
							<? echo $error; ?>
						</p>
						<? } ?>
					</div>
			 </p>
			</div>
			<div style="width:7%;float:left;">
			 <p>
		&nbsp;
			 </p>
			</div>
			<div style="width:53%;float:left;">
			 <p>
				<h4>Was können Sie tun?</h4>
				<ul>
					<li style="padding:10px;"> Warten Sie einige Augenblicke und versuchen Sie dann, die Seite erneut zu laden. Klicken Sie dazu auf diesen Link:<br><a href="index.php">Neu laden</a>
					<li style="padding:10px;"> Sollte diese Meldung danach immer noch kommen, geben Sie bitte der Geoadministration einen Hinweis. Sie können eine Email erstellen, indem Sie auf diesen Link klicken:<br><a href="mailto:<? echo $email; ?>?subject=<? echo $subject; ?>&body=<? echo $body; ?>">Hinweis versenden</a>
					<li style="padding:10px;"> Damit Sie in kvwmap weiterarbeiten können, können Sie Versuchen alle Themen auszuschalten. Klicken Sie auf diesen Link:<br><a href="index.php?go=reset_layers">Themen ausschalten</a>
				</ul>
			 </p>
			</div>
		</div>
	</body>
</html>
