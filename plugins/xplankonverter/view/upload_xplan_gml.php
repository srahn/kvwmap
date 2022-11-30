<h2 style="margin-top: 20px;"><?php echo $this->formvars['planart']; ?> Plan-Daten hochladen</h2>
<div style="padding: 20px"><?
	if (!array_key_exists('gml_file', $this->response)) { ?>
		<div id="upload_hint_div" style="text-align: left;">
			Sie können entweder nur eine XPlanGML-Datei hochladen oder eine ZIP-Datei, die neben der GML-Datei auch die Dokumente enthält auf die in den externen Referenzen der GML-Datei verwiesen wird.<p>
			Wenn Sie eine ZIP-Datei hochladen beachten Sie folgende Konventionen:
			<ol>
				<!--li>Die Dateien dürfen nicht größer als 500 MB sein.</li//-->
				<li>Die GML-Datei muss die Dateiendung <i>.gml</i> oder <i>.xml</i> haben.
					<li>Die Dateien der Plandokumente müssen die Namen haben, der auch in der externen Referenz des GML-Dokumentes im Attribut <i>referenzurl</i> angegeben ist.</li>
				<li>Die Dateien liegen in der Wurzel der ZIP-Datei, nicht in Unterverzeichnissen.</li>
				<li>Plandokumente ohne Georeferenz müssen das Format PDF haben.</li>
				<li>Die im Attribut <i>referenzurl</i> angegebene URL wird nach dem Einlesen so angepasst, dass die Dokumente von diesem Server abgerufen werden können.</li>
				<li>
					Plandokumente mit Georeferenz müssen das Format GeoTiff mit der Endung tiff haben. Dazu reicht eine Datei die zur <i>referenzURL</i> passt.<br>
					Besteht das Dokument aus Bild und Georeferenz-Datei, muss die Georeferenz-Datei im Attribut <i>georefURL</i> wie im Beispiel angegeben sein.<br>
					<pre>
						&lt;xplan:XP_ExterneReferenz&gt;
							&lt;xplan:georefURL&gt;BPlan001_5-1.pgw&lt;/xplan:georefURL&gt;
							&lt;xplan:referenzURL&gt;BPlan001_5-1.png&lt;/xplan:referenzURL&gt;
						&lt;/xplan:XP_ExterneReferenz&gt;
					</pre>
				</li>
			</ol>
		</div>

		<input type="hidden" name="go" value="xplankonverter_upload_xplan_gml"/>
		<input type="hidden" name="planart" value="<? echo $this->formvars['planart']; ?>"/>
		<input type="file" id="file_select" name="gml_file" style="height: 50px"/><br>
		<input type="submit" id="upload_button" name="upload_xplan_gml" value="Daten hochladen"/><?
	}
	else { ?>
		<div id="upload_message" style="text-align: left; padding: 20px;">
			Erfolgreich hochgeladene GML-Datei:<br>
			<ul>
				<li><b><? echo $this->response['gml_file']; ?></b></li>
			</ul><?
			echo $this->response['msg'];
			if (count($this->response['doc_files']) > 0) { ?>
				<br>Zusätzlich hochgeladene Referenzen:
				<ul>
					<li><?
						echo implode(
							'</li><li>',
							array_map(
								function($doc_file) {
									return  '<b>' . $doc_file['upload_file_name'] + '</b><br><a href="' . $this->plan_layerset['document_url'] . $doc_file['store_file_name'] . '">
										<img src="' . $this->plan_layerset['document_url'] . $doc_file['thumb_file_name'] . '" width="100" name="' . $doc_file['file_name'] . '" onmouseover="' . $doc_file['file_name'] . '.width=\'800\';" onmouseout="' . $doc_file['file_name'] . '.width=\'100\'"></a>';
								},
								$this->response['doc_files']
							)
						); ?>
					</li>
				</ul><?
			} ?>
		</div>
		<input type="hidden" name="go" value="xplankonverter_extract_gml_to_form"/>
		<input type="hidden" name="planart" value="<? echo $this->formvars['planart']; ?>"/>
		<input type="hidden" name="random_number" value="<? echo $this->response['random_number']; ?>"/>
		<input type="hidden" name="gml_file" value="<? echo $this->response['gml_file']; ?>"/>
		<input type="submit" name="Daten laden" value="Daten in Formular laden"/><?
	} ?>
</div>
<!--script>
	var form = $('#file_form');

	$('#upload_button').on(
		'click',
		function(event) {
			event.preventDefault();

			// checks if exists
			if ($('#file_select').val() == '' ) {
				message([{ type: 'error', msg: 'Keine Datei ausgewählt!'}]);
				return;
			}
			$('#upload_hint_div, #file_select, #upload_button').hide();
			$('#waiting_info_div').show();

			var file = $('#file_select')[0].files[0];
			var formData = new FormData();
			var err = '';
			formData.append('gml_file', file, file.name);
			formData.append('go', 'xplankonverter_upload_xplan_gml');
			formData.append('csrf_token', '<? echo $_SESSION['csrf_token']; ?>');
			formData.append('planart', '<? echo $this->formvars['planart']; ?>');
			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'index.php', true);
			xhr.onload = function () {
				$('#waiting_info_div').hide();
				if (xhr.status === 200) {
					result = JSON.parse(xhr.response);
					if (result.success) {
						html = 'Erfolgreich hochgeladene GML-Datei:<br><ul><li><b>' + result.gml_file + '</b></li></ul>';
						html += result.msg;
						if (result.doc_files.length > 0) {
							html += '<br>Zusätzlich hochgeladene Referenzen:<ul><li>' + result.doc_files.map(
								function(doc_file) {
									return  '<b>' + doc_file['upload_file_name'] + '</b><br><a href="<? echo $this->plan_layerset['document_url']; ?>' + doc_file['store_file_name'] + '"><img src="<? echo $this->plan_layerset['document_url']; ?>' + doc_file['thumb_file_name'] + '" width="100" name="' + doc_file['file_name'] + '" onmouseover="' + doc_file['file_name'] + '.width=\'800\';" onmouseout="' + doc_file['file_name'] + '.width=\'100\'"></a>';
								}
							).join('</li><li>') + '</li></ul>';
						}
						$('#upload_message').html(html);
						$('#random_number').val(result.random_number);
						$('#extract_to_form').val(result.gml_file).show();
					}
					else {
						err = result.msg;
					}
				}
				else {
					err = 'Fehler beim hochladen!';
				}
				if (err != '') {
					$('#upload_hint_div').show();
					$('#upload_button').show();
					$('#file_select').show();
					message([{ type: 'error', msg: err}]);
				}
			};
			xhr.onerror = function(evt) {
				console.log('Fehler beim versenden der Daten: ', evt);
				message([{ type: 'error', msg: evt}]);
			}

			xhr.send(formData);
		}
	);
</script//-->