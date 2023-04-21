class Zusammenzeichnung {
  constructor(id = null, planart, csrf_token) {
    this.xplan_version = '5.4';
    this.process = {
      upload_zusammenzeichnung: {
        nr: 1,
        msg: 'Hochladen und Validieren der Zusammenzeichnung auf den Server',
        description: 'Die Datei wird auf den Server hochgeladen und temporär abgelegt.<br>\
        Die ZIP-Datei wird entpackt und geprüft ob die notwendigen Dateien enthalten sind.<br>\
        Dann werden die Dateien an den XPlan-Validator der XPlanung-Leitstelle gesendet.<br>\
        Wenn die Validierung keinen Fehler liefert, wird eine Konvertierungsobjekt in der Datenbank angelegt und es geht weiter mit dem nächsten Schritt.<br>\
        Im Fehlerfall bekommen Sie eine Fehlermeldung und einen Link auf den Fehlerbericht.<br>\
        Sie müssten dann Ihre Dateien entsprechend des Fehlerberichtes korrigieren und können einen erneuten Versuch zum Hochladen Ihrer Zusammenzeichnung starten.'
      },
      import_zusammenzeichnung: {
        nr: 2,
        msg: 'Importieren der GML-Datei in die Portaldatenbank',
        description: 'Die Zusammenzeichnung wird in die Postgres-Datenbank mit dem Programm ogr2ogr eingelesen.<br>\
        Danach wird geprüft ob es in der Datenbank schon einen Plan gibt mit der gleichen gml-id.<br>\
        Wenn ja, abbrechen und im nächsten Schritt die gml-ids in der Zusammenzeichnung ändern.<br>\
        Wenn nicht, wird das temporär angelegte Schema umbenannt und die Datei mit den Geltungsbereichen eingelesen.<br>\
        War das erfolgreich, werden die Geometrien in die Tabelle der Geltungsbereiche für die Konvertierung übernommen.'
      },
      convert_zusammenzeichnung: {
        nr: 3,
        msg: `Konvertierung der Plandaten in die Version ${this.xplan_version}`,
        description: `Die eingelesene Zusammenzeichnung wird nun in die entsprechenden Tabellen für den Plan und die Fachobjekte übernommen.<br>\
        Dabei erfolgt das mapping zwischen dem Import-Datenmodell von ogr2ogr in das XPlanung-Datenmodell des xplankonverters.<br>\
        Bei der Konvertierung wird auch auf die Version ${this.xplan_version} gewechselt.\
        `
      },
      gml_generieren: {
        nr: 4,
        msg: 'Erzeugen der GML-Datei in Version 5.4',
        description: `In diesem Schritt wird eine neue XPlan-GML Datei in der Version ${this.xplan_version} auf den Server geschrieben.<br>\
        Diese kann später vom Nutzer heruntergeladen werden.`
      },
      create_geoweb_service: {
        nr: 5,
        msg: 'Erzeugen des GeoWeb-Dienstes für den Plan',
        description: 'Erzeugen der Map-Datei für den WMS und WFS-Dienst der Stelle.<br>\
        Update der Service-Templates.\
        '
      },
      create_metadata: {
        nr: 6,
        msg: 'Anlegen der Metadatendokumente für den Geodatensatz und die Dienste',
        description: 'Erzeugen des Metadatendokumentes zur Beschreibung des Geodatensatzes.<br>\
        Erzeugen des Metadatendokumentes zur Beschreibung des WMS-Dienstes.<br>\
        Erzeugen des Metadatendokumentes zur Beschreibung des WFS-Dienstes.<br>\
        Hochladen der Metadatendokumente in das Metainformationssystem des Portals.\
        '
      },
      update_full_geoweb_service: {
        nr: 7,
        msg: 'Aktualisieren der Landesdienste',
        description: 'Aktualisieren der Capabilities-Metadaten der Dienste in denen alle Pläne veröffentlicht werden.',
      },
      update_full_metadata: {
        nr: 8,
        msg: 'Aktualisieren der Metadatendokumente über die Landesdienste',
        description: 'Aktualisieren der Capabilities-Metadaten des Dienstes und des Geodatensatzes in denen alle Pläne veröffentlicht werden.',
      },
      replace_zusammenzeichnung: {
        nr: 9,
        msg: 'Ersetzen der alten Zusammenzeichnung durch die neue',
        description: 'Archivierung der original hochgeladenen Dateien der alten Zusammenzeichnung.<br>\
        Lösche die hochgeladenen Dateien der alten Zusammenzeichnung<br>\
        Löschen der alten Zusammenzeichnung in der Datenbank.\
        '
      },
      reindex_gml_ids: {
        nr: 10,
        msg: 'Umbenennen der GML-ID\'s',
        description: 'Es existiert schon ein Plan mit der GML-ID der hochgeladenen Zusammenzeichnung.<br>\
        Deshalb wird eine neue Zusammenzeichnung mit geänderten GML-IDs angelegt.<br>\
        Der Importprozess wird mit der geänderten Zusammenzeichnung neu gestartet.'
      },
      import_reindexed_zusammenzeichnung: {
        nr: 11,
        msg: 'Importieren der neu indizierten GML-Datei in die Portaldatenbank',
        description: 'Die Zusammenzeichnung wird erneut in die Datenbank eingelesen jedoch mit mit den umbenannten GML-IDs.<br>\
        Es laufen die gleichen Teilschritte ab wie im Schritt Importieren der GML-Datei in die Portaldatenbank.'
      },
    };
    this.confirm_class = {
      ok: 'green',
      warning: 'yellow',
      error: 'red'
    };
    this.confirm_fa_class = {
      ok: 'check',
      warning: 'question',
      error: 'exclamation'
    };
    this.id = id;
    this.planart = planart;
    this.csrf_token = csrf_token;
    this.lfdNrStep = 0;
    this.numSteps = Object.keys(this.process).length - 2;
  }

  upload_zusammenzeichnung = (event) => {
    const file_obj = event.dataTransfer.files[0];
    if (file_obj != undefined) {
      var form_data = new FormData();
      form_data.append('go', 'xplankonverter_upload_zusammenzeichnung');
      if (this.id) {
        form_data.append('konvertierung_id', this.id);
      }
      form_data.append('planart', this.planart);
      form_data.append('upload_file', file_obj);
      var xhttp = new XMLHttpRequest();
      xhttp.open("POST", "index.php", true);
      xhttp.onload = (event) => {
        if (xhttp.status == 200) {
          try {
            const response = JSON.parse(event.target.responseText);
            if (!Array.isArray(response.msg)) { response.msg = [response.msg]; }
            if (response.success) {
              this.confirm_step('upload_zusammenzeichnung', 'ok');
              this.id = response.konvertierung_id;
              $('#konvertierung_id_span').html(`Konvertierung ID: ${this.id}`).show();
              console.log('upload_zusammenzeichnung konvertierung_id: ', this.id);
              this.import_zusammenzeichnung();
            }
            else {
              this.confirm_step('upload_zusammenzeichnung', 'error');
              message([{ type: 'error', msg: response.msg }]);
            }
          } catch (err) {
            if (event.target.responseText.indexOf('<input id="login_name"') > 0) {
              window.location = 'index.php';
            }
            message([{ type: 'error', msg: 'Fehler beim Hochladen der Zusammenzeichnung!<p>' + err + ' ' + event.target.responseText }]);
          }
        }
        else {
          this.confirm_step('upload_zusammenzeichnung', 'error');
          message([{ type: 'error', msg: 'Fehler ' + xhttp.status + ' aufgetreten beim Versuch die Datei hochzuladen! ' + event.target.responseText }]);
        }
        //$('#upload_result_msg_div').show();
      }

      //      show_upload_zusammenzeichnung('Zusammenzeichnung wird verarbeitet');
      $('#sperr_div').show();
      $('#sperr_div').html('\
        <div class="xplankonverter-upload-zusammenzeichnung-div">\
          <div id="upload_zusammenzeichnung_progress_div">\
            <h2 style="margin-bottom: 20px; float: left;">Neue Zusammenzeichnung <span id="konvertierung_id_span" style="display:none"></span></h2>\
            <i class="fa fa-times" aria-hidden="true" style="float:right; margin: -5px;" onclick="cancel_upload_zusammenzeichnung()"></i>\
            <div style="clear: both"></div>\
          </div>\
          <input\
            id="upload_zusammenzeichnung_finish_button"\
            style="display: none"\
            type="button"\
            value="Zur neuen Zusammenzeichnung"\
            onclick="window.location=\'index.php?go=xplankonverter_zusammenzeichnung&planart=' + this.planart + '&csrf_token=' + this.csrf_token + '\';"\
          >\
        </div>\
      ');
      this.next_step('upload_zusammenzeichnung');
      // $('#upload_zusammenzeichnung_msg').addClass('blink');
      xhttp.send(form_data);
    }
  }

  import_zusammenzeichnung = () => {
    this.next_step('import_zusammenzeichnung');
    //console.log('import_zusammenzeichnung konvertierung_id: ', this.id);
    $.ajax({
      url: 'index.php',
      data: {
        go: 'xplankonverter_import_zusammenzeichnung',
        konvertierung_id: this.id,
        planart: this.planart,
        xplan_gml_path: 'uploaded_xplan_gml',
        mime_type: 'json',
        format: 'json_result',
      },
      success: (result) => {
        //console.log('Response import_zusammenzeichnung: %o', result);
        if (result.success) {
          this.confirm_step('import_zusammenzeichnung', 'ok')
          this.convert_zusammenzeichnung();
        }
        else {
          if (result.msg.includes('Warnung')) {
            // Auch diese Warnung soll als ok dargestellt werden.
            this.confirm_step('import_zusammenzeichnung', 'ok');
            this.numSteps = this.numSteps + 2;
            this.reindex_gml_ids();
          }
          else {
            this.confirm_step('import_zusammenzeichnung', 'error');
            console.error(result.msg);
            message([{ type: 'error', msg: 'Fehler beim Import der Zusammenzeichnung.<br>' + result.msg }]);
          }
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        this.confirm_step('import_zusammenzeichnung', 'error');
        console.error(jqXHR);
        message([{ type: 'error', msg: 'Fehler ' + textStatus + ' aufgetreten beim Versuch die Datei zu importieren! Fehlerart: ' + errorThrown }]);
      }
    })
  }

  reindex_gml_ids = () => {
    this.next_step('reindex_gml_ids');
    //console.log('reindex_gml_ids konvertierung_id: ', this.id);
    let formData = new FormData();
    formData.append('go', 'xplankonverter_reindex_gml_ids');
    formData.append('konvertierung_id', this.id);
    formData.append('planart', this.planart);
    let response = fetch('index.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(text => {
      try {
        const result = JSON.parse(text);
        if (result.success) {
          //console.log('Response reindex_gml_ids: %o', result);
          this.confirm_step('reindex_gml_ids', 'ok');
          this.import_reindexed_zusammenzeichnung('reindexed_xplan_gml');
        }
        else {
          this.confirm_step('reindex_gml_ids', 'error');
          message([{ type: 'error', msg: 'Fehler beim Umenennen der GML-ID\'s.<br>' + result.msg }]);
        }
      } catch(err) {
        this.confirm_step('reindex_gml_ids', 'error');
        message([{ type: 'error', msg: 'Fehler beim Parsen des Ergebnisses von xplankonverter_reindex_gml_id.<br>' + result.msg }]);
      }
    });
  }

  import_reindexed_zusammenzeichnung = () => {
    this.next_step('import_reindexed_zusammenzeichnung');
    //console.log('import_reindexed_zusammenzeichnung konvertierung_id: ', this.id);
    $.ajax({
      url: 'index.php',
      data: {
        go: 'xplankonverter_import_zusammenzeichnung',
        konvertierung_id: this.id,
        planart: this.planart,
        xplan_gml_path: 'reindexed_xplan_gml',
        mime_type: 'json',
        format: 'json_result',
      },
      success: (result) => {
        //console.log('Response import_reindexed_zusammenzeichnung: %o', result);
        if (result.success) {
          this.confirm_step('import_reindexed_zusammenzeichnung', 'ok')
          this.convert_zusammenzeichnung();
        }
        else {
            this.confirm_step('import_reindexed_zusammenzeichnung', 'error');
            console.error(result.msg);
            message([{ type: 'error', msg: 'Fehler beim Import der reindizierten Zusammenzeichnung.<br>' + result.msg }]);
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        this.confirm_step('import_reindexed_zusammenzeichnung', 'error');
        console.error(jqXHR);
        message([{ type: 'error', msg: 'Fehler ' + textStatus + ' aufgetreten beim Versuch die reindizierte Datei zu importieren! Fehlerart: ' + errorThrown }]);
      }
    })
  }

  convert_zusammenzeichnung = () => {
    this.next_step('convert_zusammenzeichnung');
    //console.log('convert_zusammenzeichnung konvertierung_id: ', this.id);
    $.ajax({
      url: 'index.php',
      data: {
        go: 'xplankonverter_konvertierung',
        konvertierung_id: this.id,
        planart: this.planart,
        mime_type: 'json',
        format: 'json_result',
      },
      success: (result) => {
        //console.log('Response convert_zusammenzeichnung: %o', result);
        if (result.success) {
          this.confirm_step('convert_zusammenzeichnung', 'ok');
          this.gml_generieren();
        }
        else {
          this.confirm_step('convert_zusammenzeichnung', 'error');
          message([{ type: 'error', msg: 'Fehler beim Konvertieren der Zusammenzeichnung.<br>' + result.msg }]);
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        this.confirm_step('convert_zusammenzeichnung', 'error');
        console.error(jqXHR);
        message([{ type: 'error', msg: 'Fehler ' + textStatus + ' aufgetreten beim Versuch den Zusammenzeichnung zu konvertieren! Fehlerart: ' + errorThrown }]);
      }
    })
  }

  gml_generieren = () => {
    this.next_step('gml_generieren');
    //console.log('gml_generieren konvertierung_id: ', this.id);
    $.ajax({
      url: 'index.php',
      data: {
        go: 'xplankonverter_gml_generieren',
        konvertierung_id: this.id,
        planart: this.planart,
        mime_type: 'json',
        format: 'json_result',
      },
      success: (result) => {
        //console.log('Response gml_generieren: %o', result);
        if (result.success) {
            this.confirm_step('gml_generieren', 'ok');
            this.create_geoweb_service();
         } else {
            this.confirm_step('gml_generieren', 'error');
            message([{ type: 'error', msg: 'Fehler beim Erzeugen der GML-Datei in Zielversion.<br>' + result.msg }]);
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        this.confirm_step('gml_generieren', 'error');
        console.error(jqXHR);
        message([{ type: 'error', msg: 'Fehler ' + textStatus + ' aufgetreten beim Versuch die GML-Datei in der Zielversion zu erzeugen. Fehlerart: ' + errorThrown }]);
      }
    })
  }

  create_geoweb_service = () => {
    this.next_step('create_geoweb_service');
    //console.log('create_geoweb_service konvertierung_id: ', this.id);
    $.ajax({
      url: 'index.php',
      data: {
        go: 'xplankonverter_create_geoweb_service',
        konvertierung_id: this.id,
        planart: this.planart,
        mime_type: 'json',
        format: 'json_result',
      },
      success: (result) => {
        //console.log('Response create_geoweb_service: %o', result);
        if (result.success) {
            this.confirm_step('create_geoweb_service', 'ok');
            this.create_metadata();
        }
        else {
         this.confirm_step('create_geoweb_service', 'error');
         message([{ type: 'error', msg: 'Fehler beim Erzeugen der GeoWeb-Dienste.<br>' + result.msg }]);
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        this.confirm_step('create_geoweb_service', 'error');
        console.error(jqXHR);
        message([{ type: 'error', msg: 'Fehler ' + textStatus + ' aufgetreten beim Versuch die GeoWeb-Dienste zu erzeugen. Fehlerart: ' + errorThrown }]);
      }
    })
  }

  create_metadata = () => {
    this.next_step('create_metadata');
    //console.log('create_metadata konvertierung_id: ', this.id);
    $.ajax({
      url: 'index.php',
      data: {
        go: 'xplankonverter_create_metadata',
        konvertierung_id: this.id,
        planart: this.planart,
        mime_type: 'json',
        format: 'json_result',
      },
      success: (result) => {
        console.log('Response create_metadata: %o', result);
        if (result.success) {
          this.confirm_step('create_metadata', 'ok');
          this.update_full_geoweb_service();
        }
        else {
          this.confirm_step('create_metadata', 'error');
          message([{ type: 'error', msg: 'Fehler beim Erzeugen der Daten- und Dienstemetadaten in Zielversion.<br>' + result.msg }]);
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        this.confirm_step('create_metadata', 'error');
        console.error(jqXHR);
/*
  Das ist die Meldung die in dem Step zurück kommt und deshalb einen parseerror auslößt.
     connecting: https:/mis.testportal-plandigital.de/geonetwork
     connecting: https:/mis.testportal-plandigital.de/geonetwork
     connecting: https:/mis.testportal-plandigital.de/geonetwork
    {"success":true,"msg":"Metadaten \u00fcber Daten und Dienste erfolgreich in das Metainformationssystem hochgeladen."}
 */
        message([{ type: 'error', msg: 'Fehler ' + textStatus + ' aufgetreten beim Versuch Daten- und Dienstemetadaten zu erzeugen. Fehlerart: ' + errorThrown }]);
      }
    })
  }

  update_full_geoweb_service = () => {
    this.next_step('update_full_geoweb_service');
    //console.log('update_full_geoweb_service konvertierung_id: ', this.id);
    $.ajax({
      url: 'index.php',
      data: {
        go: 'xplankonverter_create_geoweb_service',
        planart: this.planart,
        mime_type: 'json',
        format: 'json_result',
      },
      success: (result) => {
        console.log('Response update_full_geoweb_service: %o', result);
        if (result.success) {
          this.confirm_step('update_full_geoweb_service', 'ok');
          this.update_full_metadata();
        }
        else {
          this.confirm_step('update_full_geoweb_service', 'error');
          message([{ type: 'error', msg: 'Fehler beim Aktualisieren der Landesdienste.<br>' + result.msg }]);
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        this.confirm_step('update_full_geoweb_service', 'error');
        console.error(jqXHR);
        message([{ type: 'error', msg: 'Fehler ' + textStatus + ' aufgetreten beim Versuch die Landesdienste zu aktualisieren. Fehlerart: ' + errorThrown }]);
      }
    })
  }

  update_full_metadata = () => {
    this.next_step('update_full_metadata');
    //console.log('update_full_metadata konvertierung_id: ', this.id);
    $.ajax({
      url: 'index.php',
      data: {
        go: 'xplankonverter_create_metadata',
        planart: this.planart,
        mime_type: 'json',
        format: 'json_result',
      },
      success: (result) => {
        console.log('Response update_full_metadata: %o', result);
        if (result.success) {
          this.confirm_step('update_full_metadata', 'ok');
          this.replace_zusammenzeichnung();
        }
        else {
          this.confirm_step('update_full_metadata', 'error');
          message([{ type: 'error', msg: 'Fehler beim Erzeugen der Daten- und Dienstemetadaten über den Landesdienst in Zielversion.<br>' + result.msg }]);
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        this.confirm_step('update_full_metadata', 'error');
        console.error(jqXHR);
        message([{ type: 'error', msg: 'Fehler ' + textStatus + ' aufgetreten beim Versuch Daten- und Dienstemetadaten des Landesdienstes zu erzeugen. Fehlerart: ' + errorThrown }]);
      }
    })
  }

  replace_zusammenzeichnung = () => {
    this.next_step('replace_zusammenzeichnung');
    //console.log('replace_zusammenzeichnung konvertierung_id: ', this.id);
    $.ajax({
      url: 'index.php',
      data: {
        go: 'xplankonverter_replace_zusammenzeichnung',
        konvertierung_id: this.id,
        planart: this.planart,
        mime_type: 'json',
        format: 'json_result',
      },
      success: (result) => {
        //console.log('Response replace_zusammenzeichnung: %o', result);
        if (result.success) {
          this.confirm_step('replace_zusammenzeichnung', 'ok');
          $('#upload_zusammenzeichnung_finish_button').show();
        }
        else {
          this.confirm_step('replace_zusammenzeichnung', 'error');
          message([{ type: 'error', msg: 'Fehler beim Ersetzen der Vorgängerversion.<br>' + result.msg }]);
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        this.confirm_step('replace_zusammenzeichnung', 'error');
        console.error(jqXHR);
        message([{ type: 'error', msg: 'Fehler ' + textStatus + ' aufgetreten beim Versuch die Vorgängerversion zu ersetzen. Fehlerart: ' + errorThrown }]);
      }
    })
  }

  next_step = (step) => {
    //console.log('next_step %o', step);
    this.lfdNrStep++;
    $('#upload_zusammenzeichnung_progress_div').append(`\
      <div class="step">\
        <div id="upload_zusammenzeichnung_step_${this.process[step].nr}" style="float:left; font-weight: bold">Schritt ${this.lfdNrStep} von ${this.numSteps} ${this.process[step].msg}</div>\
        <div id="upload_zusammenzeichnung_step_confirm_${this.process[step].nr}" style="float: right"><i class="fa fa-spinner fa-spin" aria-hidden="true" ></i></div>\
        <div style="clear: both"></div>\
        <div id="xplankonverter-step-description-${this.process[step].nr}" class="xplankonverter-step-description"></div>\
      </div>\
    `);
    add_text_with_delay(this.process[step].description, $(`#xplankonverter-step-description-${this.process[step].nr}`));
  }

  confirm_step = (step, success) => {
    //console.log('confirm_step: %o', this.process[step]);
    $('#upload_zusammenzeichnung_step_' + this.process[step].nr).addClass(this.confirm_class[success]);
    $('#upload_zusammenzeichnung_step_confirm_' + this.process[step].nr).html('<i class="fa fa-' + this.confirm_fa_class[success] + ' ' + this.confirm_class[success]  + '" aria-hidden="true" ></i>');
    if (!success) {
      $('#upload_zusammenzeichnung_msg').removeClass('blink');
      $('#upload_zusammenzeichnung_div').removeClass('dragover');
    }
  }
}