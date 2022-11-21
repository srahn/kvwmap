class Zusammenzeichnung {
  constructor(id) {
    this.process = {
      upload_zusammenzeichnung : {
        nr : 1,
        msg : 'Hochladen der Zusammenzeichnung auf den Server'
      },
      validate_zusammenzeichnung : {
        nr : 2,
        msg : 'Validierung der hochgeladenen GML-Datei mit dem XPlanValidator'
      },
      import_zusammenzeichnung : {
        nr : 3,
        msg : 'Importieren der GML-Datei in die Portaldatenbank'
      },
      convert_zusammenzeichnung : {
        nr : 4,
        msg : 'Konvertierung der Plandaten in die Version 5.4'
      },
      create_gml_file : {
        nr : 5,
        msg : 'Erzeugen der GML-Datei in Version 5.4'
      },
      create_geoweb_service : {
        nr : 6,
        msg : 'Erzeugen des GeoWeb-Dienstes für den Plan'
      },
      create_service_metadata : {
        nr : 7,
        msg : 'Anlegen der Metadaten für den Dienst'
      }
    };
    this.id = id;
  }

  upload_zusammenzeichnung = (event, konvertierung_id) => {
    const file_obj = event.dataTransfer.files[0];
    if (file_obj != undefined) {
      var form_data = new FormData();
      form_data.append('go', 'xplankonverter_upload_zusammenzeichnung');
      form_data.append('konvertierung_id', konvertierung_id);
      form_data.append('upload_file', file_obj);
      var xhttp = new XMLHttpRequest();
      xhttp.open("POST", "index.php", true);
      xhttp.onload = (event) => {
        if (xhttp.status == 200) {
          try {
            const response = JSON.parse(event.target.responseText);
            if (!Array.isArray(response.msg)) { response.msg = [response.msg]; }
            if (response.success) {
              this.confirm_step('upload_zusammenzeichnung', true);
              this.validate_zusammenzeichnung();
            }
            else {
              this.confirm_step('upload_zusammenzeichnung', false);
              message(response.msg);
            }
          } catch (err) {
            if (event.target.responseText.indexOf('<input id="login_name"') > 0) {
              window.location = 'index.php';
            }
            this.show_upload_zusammenzeichnung('Neue Version der Zusammenzeichnung hier reinziehen.');
            $('#upload_zusammenzeichnung_msg').removeClass('blink');
            $('#upload_zusammenzeichnung_div').removeClass('dragover');
            message([{ type: 'error', msg: 'Fehler beim Hochladen der Zusammenzeichnung!<p>' + err + ' ' + event.target.responseText }]);
          }
        }
        else {
          this.confirm_step('upload_zusammenzeichnung', false);
          message([{ type: 'error', msg: 'Fehler ' + xhttp.status + ' aufgetreten beim Versuch die Datei hochzuladen! ' + event.target.responseText }]);
        }
        //$('#upload_result_msg_div').show();
      }

//      show_upload_zusammenzeichnung('Zusammenzeichnung wird verarbeitet');
      $('#sperr_div').show();
      $('#sperr_div').html('\
        <div id="upload_zusammenzeichnung_progress_div" class="xplankonverter-upload-zusammenzeichnung-div">\
          <h2 style="margin-bottom: 20px; float: left;">Neue Zusammenzeichnung</h2>\
          <i class="fa fa-times" aria-hidden="true" style="float:right; margin: -5px;" onclick="cancel_upload_zusammenzeichnung()"></i>\
          <div style="clear: both"></div>\
        </div>\
      ');
      this.next_step('upload_zusammenzeichnung');
//      $('#upload_zusammenzeichnung_msg').addClass('blink');
      xhttp.send(form_data);
    }
  }

  validate_zusammenzeichnung = () => {
    this.next_step('validate_zusammenzeichnung');
    $.ajax({
      url: 'index.php',
      data: {
        go: 'xplankonverter_validate_zusammenzeichnung',
        konvertierung_id : this.id,
        mime_type: 'json',
        format: 'json_result',
      },
      success: (result) => {
        console.log('Response validate_zusammenzeichnung: %o', result);
        if (result.success) {
          if (result.valid) {
            this.confirm_step('validate_zusammenzeichnung', true);
            this.import_zusammenzeichnung();
          }
          else {
            this.confirm_step('validate_zusammenzeichnung', false);
            this.confirm_step('upload_zusammenzeichnung', false);
            message([{ type: 'error', msg: 'Fehler bei der Validierung der Zusammenzeichnung.<br>' + result.msg }]);
          }
        }
        else {
          this.confirm_step('validate_zusammenzeichnung', false);
          console.error(result.msg);
          message([{ type: 'error', msg: 'Fehler beim Validieren der Datei. ' + result.msg }]);
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        this.confirm_step('validate_zusammenzeichnung', false);
        console.error(jqXHR);
        message([{ type: 'error', msg: 'Fehler ' + textStatus + ' aufgetreten beim Versuch die Datei hochzuladen! Fehlerart: ' + errorThrown }]);
      }
    })
  }

  import_zusammenzeichnung = () => {
    this.next_step('import_zusammenzeichnung');
    this.confirm_step('import_zusammenzeichnung', true);
    this.convert_zusammenzeichnung();
  }

  convert_zusammenzeichnung = () => {
    this.next_step('convert_zusammenzeichnung');
    this.confirm_step('convert_zusammenzeichnung', true);
    this.create_gml_file();
  }

  create_gml_file = () => {
    this.next_step('create_gml_file');
    this.confirm_step('create_gml_file', true);
    this.create_geoweb_service();
  }

  create_geoweb_service = () => {
    this.next_step('create_geoweb_service');
    this.confirm_step('create_geoweb_service', true);
    this.create_service_metadata();
  }

  create_service_metadata = () => {
    this.next_step('create_service_metadata');
    this.confirm_step('create_service_metadata', true);
  }

  next_step = (step) => {
    console.log('next_step %o', step);
    $('#upload_zusammenzeichnung_progress_div').append('\
      <div class="step">\
        <div id="upload_zusammenzeichnung_step_' + this.process[step].nr + '" style="float:left">' + this.process[step].msg + '</div>\
        <div id="upload_zusammenzeichnung_step_confirm_' + this.process[step].nr + '" style="float: right"><i class="fa fa-spinner fa-spin" aria-hidden="true" ></i></div>\
        <div style="clear: both"></div>\
      </div>\
    ');
  }

  confirm_step = (step, success) =>  {
    console.log('confirm_step: %o', this.process[step]);
    $('#upload_zusammenzeichnung_step_' + this.process[step].nr).addClass(success ? 'green' : 'red');
    $('#upload_zusammenzeichnung_step_confirm_' + this.process[step].nr).html('<i class="fa fa-' + (success ? 'check green' : 'exclamation red') + '" aria-hidden="true" ></i>');
  }  
}