<!DOCTYPE html>
<html lang="de" dir="ltr">
<head>
	<meta charset="utf-8">
	<? include(SNIPPETS . 'gui_head.php'); ?>
	<script>
		$('iframe').load(function() {
			this.style.height =
			this.contentWindow.document.body.offsetHeight + 'px';
		});
	</script>
</head>
	<body>
		<div style="margin: 20px">
			<h1>Tests für Loginfunktionen</h1>

			<h2>1. Schon angemeldet, keine Login-Anfrage, keine neue Stelle</h2>
			<b>Eingangsparameter:</b>
			</br>
			<ul>
				<li>angemeldet (vorher <a href="index.php?login_name=korduan&passwort=xxx" target="kvwmap_test_window">anmelden</a>)</li>
				<li>ohne Parameter</li>
			</ul>
			<? $url = 'index.php'; ?>
			<a href="<?php echo $url; ?>" target="kvwmap_test_window"><?php echo $url; ?></a>
			<p>
			<b>Flow:</b>
			<ul>
				<li>Ist angemeldet.</li>
				<li>Lade Stelle und Rolle</li>
			</ul>
			<b>Erwartetes Ergebnis</b><br>
			<ul>
				<li>Startseite in letzter Stelle.</li>
				<li><span style="background-color: green">bestanden</span></li>
			</ul>

			<h2>2. Neuer Login als Gast, mit browser size</h2>
			<b>Eingangsparameter:</b>
			<ul>
				<li>nicht angemeldet (vorher <a href="index.php?go=logout" target="kvwmap_test_window">ausloggen</a>)</li>
				<li>gast=Stellen_ID, browserwidth und browserheight > 0</li>
			</ul>
			<? $url = 'index.php?gast=55&browserwidth=1200&browserheight=800'; ?>
			<a href="<?php echo $url; ?>" target="kvwmap_test_window"><?php echo $url; ?></a>
			<p>
			<b>Flow:</b>
			<ul>
				<li>Nicht angemeldet.</li>
				<li>Es ist eine Gastanmeldung.</li>
				<li>Hat width und height.</li>
			</ul>
			<b>Erwartetes Ergebnis:</b>
			<ul>
				<li>Startseite in Gaststelle. Gastrolle angelegt.</li>
				<li><span style="background-color: green">bestanden</span></li>
			</ul>

			<h2>3. Login als Gast, ohne browser size</h2>
			<b>Eingangsparameter:</b>
			<ul>
				<li>Nicht angemeldet, (vorher <a href="index.php?go=logout" target="kvwmap_test_window">ausloggen</a>).</li>
				<li>gast=Stellen_ID</li>
			</ul>
			<? $url = 'index.php?gast=55'; ?>
			<a href="<?php echo $url; ?>" target="kvwmap_test_window"><?php echo $url; ?></a>
			<p>
			<b>Flow:</b>
			<br>1. Aufruf
			<ul>
				<li>Nicht angemeldet.</li>
				<li>Es ist eine Gastanmeldung.</li>
				<li>Hat keine width und height. Frage sie ab.</li>
			</ul>
			<br>2. Aufruf
			<ul>
				<li>Nicht angemeldet.</li>
				<li>Es ist eine Gastanmeldung.</li>
				<li>Hat width und height.</li>
				<li>Set Session</li>
				<li>Verbindung zur PostGIS Datenbank erfolgreich hergestellt.</li>
			</ul>
			<b>Erwartetes Ergebnis:</b>
			<ul>
				<li>Kurz Loginseite zum Automatischen Abfragen von width und height.</li>
				<li>Nach Neuladne der Seite Startseite in Gaststelle mit passender Size.</li>
				<li>Gastrolle angelegt.</li>
				<li><span style="background-color: green">bestanden</span></li>
			</ul>

			<h2>4. Login mit Nutzername und Passwort</h2>
			<b>Eingangsparameter:</b>
			<ul>
				<li>Nicht angemeldet (vorher <a href="index.php?go=logout" target="kvwmap_test_window">ausloggen</a>).</li>
				<li>korrekte user_name</li>
				<li>kein password</li>
				<li>kein new_password</li>
			</ul>
			<? $url = 'index.php?login_name=korduan&passwort=xxx'; ?>
			<a href="<?php echo $url; ?>" target="kvwmap_test_window"><?php echo 'index.php?login_name=korduan&passwort=xxx'; ?></a>
			<p>
			<b>Flow:</b>
			<ul>
				<li>Nicht angemeldet.</li>
				<li>Es ist keine Gastanmeldung.</li>
				<li>Es ist eine reguläre Anmeldung</li>
				<li>Anmeldung war erfolgreich. Frage alle Stellen des Nutzers ab.</li>
				<li>Set Session</li>
				<li>Verbindung zur PostGIS Datenbank erfolgreich hergestellt.</li>
			</ul>
			<b>Erwartetes Ergebnis</b><br>
			<ul>
				<li>Startseite letzte Stelle</li>
				<li><span style="background-color: green">bestanden</span></li>
			</ul>

			<h2>5. Login mit neuem Passwort</h2>
			<b>Eingangsparameter:</b>
			<ul>
				<li>Nicht angemeldet (vorher <a href="index.php?go=logout" target="kvwmap_test_window">ausloggen</a>).</li>
				<li>login_name</li>
				<li>Das alte gültige Passwort</li>
				<li>Das 1. neue Passwort</li>
				<li>Das 2. neue Passsort, dass identisch ist mit 1. und den Vorschriften für Passwörter nach Konstanten PASSWORD_... in config.php entsprechen.</li>
			</ul>
			<? $url = 'index.php?login_name=korduan&passwort=xxx&new_password=yyy&new_password_2=yyy'; ?>
			<a href="<?php echo $url; ?>" target="kvwmap_test_window"><?php echo $url; ?></a>
			<p>
			<b>Flow:</b>
			<ul>
				<li>Nicht angemeldet (vorher <a href="index.php?go=logout" target="kvwmap_test_window">ausloggen</a>)</li>
				<li>Es ist keine Gastanmeldung.</li>
				<li>Es ist eine reguläre Anmeldung.</li>
				<li>Anmeldung war erfolgreich. Frage alle Stellen des Nutzers ab.</li>
				<li>Es wurde ein neues Passwort angegeben.</li>
				<li>Neues Password ist valid.</li>
				<li>Keine neue Stelle angefragt. Stelle: 54 bleibt.</li>
				<li>Zugang zu Stelle 54 wird angefragt.</li>
				<li>Nutzer gehört zur Stelle 54</li>
				<li>Passwort ist nicht abgelaufen.</li>
				<li>Es wird geprüft ob IP-Adressprüfung in der Stelle durchgeführt werden muss.</li>
				<li>IP-Adresse des Clients wird in dieser Stelle geprüft.</li>
				<li>Nutzer ist in Stelle 54 erlaubt.</li>
				<li>Lade Stelle und Rolle.</li>
				<li><span style="background-color: green">bestanden</span></li>
			</ul>

			<h2>6. Login mit neuem Passwort schlägt fehl</h2>
			<b>Eingangsparameter:</b>
			<ul>
				<li>Nicht angemeldet (vorher <a href="index.php?go=logout" target="kvwmap_test_window">ausloggen</a>).</li>
				<li>login_name</li>
				<li>Das alte gültige Passwort</li>
				<li>Das 1. neue Passwort</li>
				<li>Das 2. neue Passsort, dass identisch ist mit 1. und den Vorschriften für Passwörter nach Konstanten PASSWORD_... in config.php entsprechen.</li>
			</ul>
			<? $url = 'index.php?login_name=korduan&passwort=xxx&new_password=yyy&new_password_2=yyy'; ?>
			<a href="<?php echo $url; ?>" target="kvwmap_test_window"><?php echo $url; ?></a>
			<p>
			<b>Flow:</b>
			<ul>
				<li>Nicht angemeldet (vorher <a href="index.php?go=logout" target="kvwmap_test_window">ausloggen</a>)</li>
				<li>Es ist keine Gastanmeldung.</li>
				<li>Es ist eine reguläre Anmeldung.</li>
				<li>Anmeldung war erfolgreich. Frage alle Stellen des Nutzers ab.</li>
				<li>Es wurde ein neues Passwort angegeben.</li>
				<li>Neues Password ist nicht valid. Zurück zur Anmeldung mit Fehlermeldung.</li>
				<li><span style="background-color: green">bestanden</span></li>
			</ul>

			<h2>7. Login schlägt fehl</h2>
			<b>Eingangsparameter:</b>
			<ul>
				<li>Nicht angemeldet (vorher <a href="index.php?go=logout" target="kvwmap_test_window">ausloggen</a>).</li>
				<li>falsche Kombination von user_name und passwort</li>
			</ul>
			<? $url = 'index.php?login_name=korduan&passwort=xxx'; ?>
			<a href="<?php echo $url; ?>" target="kvwmap_test_window"><?php echo $url; ?></a>
			<p>
			<b>Flow:</b>
			<ul>
				<li>Nicht angemeldet (vorher <a href="index.php?go=logout" target="kvwmap_test_window">ausloggen</a>)</li>
				<li>Es ist keine Gastanmeldung.</li>
				<li>Es ist eine reguläre Anmeldung.</li>
				<li>Anmeldung ist fehlgeschlagen.</li>
				<li><span style="background-color: green">bestanden</span></li>
			</ul>

			<h2>8.1 Zum Login ohne Parameter</h2>
			<b>Eingangsparameter:</b>
			</br>
			<ul>
				<li>nicht angemeldet (vorher <a href="index.php?go=logout" target="kvwmap_test_window">ausloggen</a>)</li>
				<li>ohne Parameter</li>
			</ul>
			<? $url = 'index.php'; ?>
			<a href="<?php echo $url; ?>" target="kvwmap_test_window"><?php echo $url; ?></a>
			<p>
			<b>Flow:</b>
			<ul>
				<li>Nicht angemeldet (vorher <a href="index.php?go=logout" target="kvwmap_test_window">ausloggen</a>)</li>
				<li>Es ist keine Gastanmeldung.</li>
				<li>Es ist keine Anmeldung.</li>
				<li>Es ist keine Registrierung. Zeige Login-Formular</li>
			</ul>
			<b>Erwartetes Ergebnis</b><br>
			<ul>
				<li>Login-Seite ohne voreingestellte Parameter</li>
				<li>ohne zweites Passwort</li>
				<li>ohne Fehler</li>
				<li><span style="background-color: green">bestanden</span></li>
			</ul>

			<h2>8.2 Zum Login mit Nutzername</h2>
			<b>Eingangsparameter:</b>
			</br>
			<ul>
				<li>nicht angemeldet (vorher <a href="index.php?go=logout" target="kvwmap_test_window">ausloggen</a>)</li>
				<li>mit login_name</li>
			</ul>
			<? $url = 'index.php?login_name=kvwmap'; ?>
			<a href="<?php echo $url; ?>" target="kvwmap_test_window"><?php echo $url; ?></a>
			<p>
			<b>Flow:</b>
			<ul>
				<li>Nicht angemeldet.</li>
				<li>Es ist keine Gastanmeldung.</li>
				<li>Es ist keine Anmeldung.</li>
				<li>Es ist keine Registrierung. Zeige Login-Formular</li>
			</ul>
			<b>Erwartetes Ergebnis</b><br>
			<ul>
				<li>Login-Seite mit voreingestelltem Nutzername</li>
				<li>ohne zweites Passwort</li>
				<li>ohne Fehler.</li>
				<li><span style="background-color: green">bestanden</span></li>
			</ul>

			<h2>9. Anmeldung zur Registrierung </h2>
			<b>Eingangsparameter:</b>
			</br>
			<ul>
				<li>nicht angemeldet (vorher <a href="index.php?go=logout" target="kvwmap_test_window">ausloggen</a>)</li>
				<li>Parameter:</li>
				<ul>
					<li>token=ab0671b0-39ae-11e8-9ba7-bb07f51b01a2</li>
					<li>email=peter.korduan@gdi-service.de</li>
				</ul>
				<li>kein Passwort gesetzt</li>
				<li>token ist für email registriert</li>
				<li>die für den token eingetragene Stelle 55 existiert</li>
			</ul>
			<? $url = 'index.php?token=ab0671b0-39ae-11e8-9ba7-bb07f51b01a2&email=peter.korduan@gdi-service.de&stelle_id=64'; ?>
			<a href="<?php echo $url; ?>" target="kvwmap_test_window"><?php echo $url; ?></a>
			<p>
			<b>Flow:</b>
			<ul>
				<li>Nicht angemeldet.</li>
				<li>Es ist keine Gastanmeldung.</li>
				<li>Es ist keine Anmeldung.</li>
				<li>Es ist eine Registrierung.</li>
				<li>Es wurde noch kein neues Passwort für die Registrierung vergeben.</li>
			</ul>
			<p>
			<b>Erwartetes Ergebnis</b>
			<ul>
				<li>Anzeige Registrierungsfenster</li>
			</ul>

			<h2>10. Registrierung erfolgreich</h2>
			<b>Eingangsparameter:</b>
			</br>
			<ul>
				<li>nicht angemeldet (vorher <a href="index.php?go=logout" target="kvwmap_test_window">ausloggen</a>)</li>
				<li>Parameter:</li>
				<ul>
					<li>token=ab0671b0-39ae-11e8-9ba7-bb07f51b01a2</li>
					<li>email=peter.korduan@gdi-service.de</li>
				</ul>
				<li>token ist für email registriert</li>
				<li>die für den token eingetragene Stelle 55 existiert</li>
			</ul>
			<? $url = 'index.php?token=ab0671b0-39ae-11e8-9ba7-bb07f51b01a2&email=peter.korduan@gdi-service.de'; ?>
			<a href="<?php echo $url; ?>" target="kvwmap_test_window"><?php echo $url; ?></a>
			<p>
			<b>Flow:</b>
			<ul>
				<li>Nicht angemeldet.</li>
				<li>Es ist keine Gastanmeldung.</li>
				<li>Es ist keine Anmeldung.</li>
				<li>Es ist eine Registrierung.</li>
				<li>Registrierung mit neuem Passwort.</li>
				<li>Registrierung ist valide.</li>
			</ul>
			<p>
			<b>Erwartetes Ergebnis</b>
			<ul>
				<li>Neue Nutzer mit der Email angelegt und der Stelle zugewiesen.</li>
				<li>Anzeige der Karte in Stelle die für Registrierung eingetragen war.</li>
			</ul>

			<h2>11. Registrierung nich valide</h2>
			<b>Eingangsparameter:</b>
			</br>
			<ul>
				<li>nicht angemeldet (vorher <a href="index.php?go=logout" target="kvwmap_test_window">ausloggen</a>)</li>
				<li>Parameter:</li>
				<ul>
					<li>token=ab0671b0-39ae-11e8-9ba7-bb07f51b01a2</li>
					<li>email=peter.korduan@gdi-service.de</li>
				</ul>
				<li>mögliche Varianten für Registrierung nicht valide:</li>
				<ul>
					<li>token passt nicht zu email</li>
					<li>Passwort nicht valide</li>
					<li>login_name schon vergeben</li>
				</ul>
			</ul>
			<? $url = 'index.php?token=ab0671b0-39ae-11e8-9ba7-bb07f51b01a2&email=peter.xxx@gdi-service.de'; ?>
			<a href="<?php echo $url; ?>" target="kvwmap_test_window"><?php echo $url; ?></a>
			<p>
			<b>Flow:</b>
			<ul>
				<li>Nicht angemeldet.</li>
				<li>Es ist keine Gastanmeldung.</li>
				<li>Es ist keine Anmeldung.</li>
				<li>Es ist eine Registrierung.</li>
				<li>Registrierung mit neuem Passwort.</li>
				<li>Registrierung ist nicht valide.</li>
			</ul>
			<p>
			<b>Erwartetes Ergebnis</b>
			<ul>
				<li>Registrierungsformular mit Fehlermeldung</li>
			</ul>

			<h2>16. Passwort abgelaufen</h2>
			<b>Eingangsparameter:</b>
			<ul>
				<li>Ist abgemeldet (vorher <a href="index.php?go=logout" target="kvwmap_test_window">ausloggen</a>)</li>
				<li>login_name</li>
				<li>passwort</li>
				<li>user gehört zur Stelle</li>
				<li>passort age wird geprüft</li>
				<li>Passwordlebensdauer wird in Stelle geprüft</li>
				<li>Passwortabgelaufen</li>
			</ul>
			<? $url = 'index.php?go=logout&login_name=korduan&passwort=xxx'; ?>
			<a href="<?php echo $url; ?>" target="kvwmap_test_window"><?php echo $url; ?></a>

			<b>Flow</b>
			<ul>
				<li>Nicht angemeldet</li>
				<li>Es ist keine Gastanmeldung.</li>
				<li>Es ist eine reguläre Anmeldung.</li>
				<li>Anmeldung war erfolgreich. Frage alle Stellen des Nutzers ab.</li>
				<li>Es wurde kein neues Passwort angegeben.</li>
				<li>Zugang zu Stelle $stelle_id wird angefragt.</li>
				<li>Keine neue Stelle angefragt. Stelle: 54 bleibt.</li>
				<li>Zugang zu Stelle 54 wird angefragt.</li>
				<li>Nutzer gehört zur Stelle 54</li>
				<li>Passwort ist abgelaufen.</li>
				<li>Zugang zur Stelle 54 für Nutzer nicht erlaubt weil: password expired</li>
				<li>Kein OWS Request.</li>
				<li>Passwort ist abgelaufen. Frage neues ab.</li>
			</ul>
			<b>Erwartetes Ergebnis</b>
			<ul>
				<li>Anmeldefenster zur Festlegung eines neuen Passwortes</li>
				<li>Nutzername nicht editierbar</li>
				<li>Passwort leer</li>
			</ul>

			<h2>12. Logout aus angemeldet.</h2>
			<b>Eingangsparameter:</b>
			<ul>
				<li>ist Angemeldet (vorher <a href="index.php?login_name=korduan&passwort=xxx" target="kvwmap_test_window">anmelden</a>)</li>
				<li>go=logout</li>
			</ul>
			<? $url = 'index.php?go=logout'; ?>
			<a href="<?php echo $url; ?>" target="kvwmap_test_window"><?php echo $url; ?></a>
			<p>
			<b>Flow:</b>
			<ul>
				<li>Ist angemeldet und Logout.</li>
				<li>Nicht angemeldet.</li>
				<li>Es ist keine Gastanmeldung.</li>
				<li>Es ist keine Anmeldung.</li>
				<li>Es ist keine Registrierung. Zeige Login-Formular</li>
			</ul>
			<b>Erwartetes Ergebnis</b><br>
			<ul>
				<li>Loginseite ohne Parameter</li>
			</ul>
			<p>

				<h2>14. User gehört nicht zur Stelle</h2>
				<b>Eingangsparameter:</b>
				<ul>
					<li>Ist abgemeldet (vorher <a href="index.php?go=logout" target="kvwmap_test_window">ausloggen</a>)</li>
					<li>login_name</li>
					<li>passwort</li>
					<li>user gehört nicht zur Stelle</li>
				</ul>
				<? $url = 'index.php?go=logout&login_name=korduan&passwort=xxx'; ?>
				<a href="<?php echo $url; ?>" target="kvwmap_test_window"><?php echo $url; ?></a>

				<b>Flow</b>
				<ul>
					<li>Nicht angemeldet</li>
					<li>Es ist keine Gastanmeldung.</li>
					<li>Es ist eine reguläre Anmeldung.</li>
					<li>Anmeldung war erfolgreich. Frage alle Stellen des Nutzers ab.</li>
					<li>Es wurde kein neues Passwort angegeben.</li>
					<li>Zugang zu Stelle $stelle_id wird angefragt.</li>
					<li>Keine neue Stelle angefragt. Stelle: 54 bleibt.</li>
					<li>Zugang zu Stelle 54 wird angefragt.</li>


					<li>Zugang zur Stelle 54 für Nutzer nicht erlaubt weil: password expired</li>
					<li>Kein OWS Request.</li>
					<li>Passwort ist nicht abgelaufen</li>
				</ul>
				<b>Erwartetes Ergebnis</b>
				<ul>
					<li>Stelle Wählen in der letzten Stelle in der der User zuletzt angemeldet oder zugeordnet war.</li>
				</ul>
		</div>
	</body>
</html>
