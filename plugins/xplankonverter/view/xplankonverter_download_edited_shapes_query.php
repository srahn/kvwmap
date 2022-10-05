<div style="float:left; width: 90%">
	<h2>Download Dateien</h2><?
	if ($this->files_exists) { ?>
		Daten zum Download vorhanden (<? echo $this->formvars['file_type']; ?>).<br><br>
		Klicken Sie auf das Symbol und der Download-Dialog öffnet sich: <?
	}
	else { ?>
		Keine Daten vom Typ <? echo $this->formvars['file_type']; ?> zum Download vorhanden!<br><br><?
	}
	switch ($this->formvars['file_type']) {
		case 'uploaded_shape_files' : {
			if ($this->files_exists) { ?>
				<a title="Hochgeladene Shapes" class="btn btn-link btn-xs xpk-func-download-gml xpk-func-btn" href="index.php?go=xplankonverter_download_uploaded_shapes&konvertierung_id=<? echo $this->formvars['konvertierung_id']; ?>"><i class="fa fa-lg fa-file-photo-o" style="color: green;"></i></a><?
			}
			else { ?>
				Es wurden noch keine Shape-Dateien zur Konvertierung hochgeladen oder sie wurden anschließend wieder gelöscht.<?
			}
		} break;
		case 'edited_shape_files' : {
			if ($this->files_exists) { ?>
				<a title="Geänderte Shapes" class="btn btn-link btn-xs xpk-func-download-gml xpk-func-btn" href="index.php?go=xplankonverter_download_edited_shapes&konvertierung_id=<? echo $this->formvars['konvertierung_id']; ?>"><i class="btn-link fa fa-lg fa-file-image-o" style="color: orange;"></i></a><?
			}
			else { ?>
				Es wurden keine Shape-Dateien bearbeitet. Daher stehen auch keine für den Download zur Verfügung.<?
			}
		} break;
		case 'xplan_gml_file' : {
			if ($this->files_exists) { ?>
				<a title="XPlan-GML Datei" class="btn btn-link btn-xs xpk-func-download-gml xpk-func-btn" href="index.php?go=xplankonverter_download_xplan_gml&konvertierung_id=<? echo $this->formvars['konvertierung_id']; ?>"><i class="btn-link fa fa-lg fa-file-excel-o" style="color: red;"></i></a><?
			}
			else { ?>
				Es wurde keine XPlan-GML Datei gefunden. Prüfen Sie ob die Konvertierung schon erfolgreich ausgeführt wurde.<?
			}
		} break;
		case 'xplan_shape_files' : {
			if ($this->files_exists) { ?>
				<a title="XPlanung Shapes" class="btn btn-link btn-xs xpk-func-download-gml xpk-func-btn" href="index.php?go=xplankonverter_download_xplan_shapes&konvertierung_id=<? echo $this->formvars['konvertierung_id']; ?>"><i class="btn-link fa fa-lg fa-file-picture-o" style="color: red;"></i></a><?
			}
			else { ?>
				Für den Plan existieren noch keine Fachdaten. Deshalb können auch keine XPlanung konformen Shape-Dateien heruntergeladen werden. Sie müssen erst Shapes hochladen, Regeln für die Konvertierung definieren, die Konvertierung durchführen und dann stehen die XPlan-Shapes zum Download zur Vergügung.<?
			}
		} break;
		case 'inspire_gml_file' : {
			if ($this->files_exists) { ?>
				<a title="INSPIRE-GML Datei" class="btn btn-link btn-xs xpk-func-download-gml xpk-func-btn" href="index.php?go=xplankonverter_download_inspire_gml&konvertierung_id=<? echo $this->formvars['konvertierung_id']; ?>"><i class="btn-link fa fa-lg fa-file-code-o" style="color: blue;"></i></a><?
			}
			else { ?>
				Es wurde keine INSPIRE-GML Datei zum Download gefunden. Sie müssen erst die Konvertierung nach INSPIRE-GML über den Button <i class="btn-link fa fa-lg fa-globe"></i> starten. Dann können Sie hier die Datei erunterladen.<?
			}
		} break;
		case 'geoweb_service_capabilities' : { ?>
			Noch nicht implementiert<p>
			Hier erscheint der Link zum Download des Capabilities-Dokumentes des Dienstes<p>
			<a title="GeoWebDienst Capabilities Datei" class="btn btn-link btn-xs xpk-func-download-gml xpk-func-btn" href="index.php?go=xplankonverter_download_service_capabilities&konvertierung_id=<? echo $this->formvars['konvertierung_id']; ?>"><i class="btn-link fa fa-lg fa-file-code-o" style="color: blue;"></i></a><?
		} break;
		case 'geoweb_service_metadata' : { ?>
			Noch nicht implementiert<p>
			Hier erscheint der Link zum Download des Metadatendokumentes des Dienstes<p>
			<a title="GeoWebDienst Metadaten-Datei" class="btn btn-link btn-xs xpk-func-download-gml xpk-func-btn" href="index.php?go=xplankonverter_download_service_metadata&konvertierung_id=<? echo $this->formvars['konvertierung_id']; ?>"><i class="btn-link fa fa-lg fa-file-code-o" style="color: blue;"></i></a><?
		} break;
		default : { ?>
			Beachten Sie den Bearbeitungsstatus der Konvertierung.<?
		}
	} ?>
</div>
<div style="float:right; margin: 5px; cursor: pointer;" onclick="$('#downloadMessage').hide().html(''); $('#downloadMessageSperrDiv').hide();">
	<i class="fa fa-lg fa-close" style="color: gray;"></i>
</div>