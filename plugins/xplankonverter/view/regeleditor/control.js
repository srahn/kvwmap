"use strict";
// ----------------------------------------------------------------------------------------------------
// Information & Basic
// ----------------------------------------------------------------------------------------------------

function getRelPath() {
  return 'plugins/xplankonverter/view/regeleditor/';
}

function setValue() {
  var field_id = document.getElementById("field_id").value;
  // Strips the HTML tags from sql_ausgabe
  var sqlString = $("#sql_ausgabefenster")[0];
  var sqlString = $(sqlString).text();
  // Strips empty lines at the beginning and end of the string
  sqlString = sqlString.trim();
  //Replaces multiple spaces with a single space
  sqlString = sqlString.replace(/\s\s+/g, " ");
  // If the last word in string is WHERE, it will get removed
  var n = sqlString.split(" ");
  if(n[n.length -1] = "WHERE"){
    //removes last word WHERE and last space
    sqlString = sqlString.substring(0,sqlString.length -6);
  }
  // Sets the sql in the kvwmap-form
  top.document.getElementById(field_id).value = sqlString;
  top.closeCustomSubform();
}

function infoPage() {
  var modal = $("#myModal")[0];
  var span = document.getElementsByClassName("close")[0];
  modal.style.display = "block";
  // When the user clicks on <span> (x), close the modal
  span.onclick = function() {
    modal.style.display = "none";
  }
  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
  }
}

function distinctValues(clicked_id) {
  var shape =  $("#source").html(),
      shpAttribut = clicked_id.substring(9), //ShapeFile + _ + ShapeAttribut
      shpDistinctValues = $("#distinctValues_" + shape + "_" + shpAttribut);

  if(shpDistinctValues.is(":visible")){
    shpDistinctValues.hide();
  } else {
    shpDistinctValues.show();
  }
}

// ----------------------------------------------------------------------------------------------------
// Setting
// ----------------------------------------------------------------------------------------------------
function setShapefile() {
  var shapefile = $("#source_selector")[0].value;
  $("#fester_wert_text")[0].value = $("#fester_wert_text")[0].defaultValue;
  $("#sql_shape_table").html(shapefile);
  $("#" + shapefile+ "_source_attributes").show();
  $("#source").html(shapefile);
  $("#source_selector").hide();
  $("#Warnung_2").hide(); // Keine Shapefile gewählt
  getShapeAttributes(); // Weist Felder für Zuweisungen durch AJAX zu
  getShapeAttributes2(); // Setzt Felder für Wenn Dann und alle aus SHP
  getShapeAttributes3(); // Setzt Filter für WHERE-Kondition
  showSqlArea();
}

function chooseFeatureTable() {
  getXPlanAttributes();
  $("#target_selector").hide();
  $("#Warnung_1").hide(); // Kein FeatureType gewählt
  $("#Warnung_3").show(); // Kein Rechtscharakter gewählt
  var featuretype = $("#target_selector")[0].value;
  $("#target").html(featuretype);
  // Trägt in Featuretype in INSERT INTO in SQL ein
  $("#sql_insertinto_featuretype").replaceWith(featuretype);
  showSqlArea();
}

function showSqlArea() {
  var shapefileTable = $("#source_selector").val();
  var xplanTable = $("#target_selector").val();
  if($("#" + shapefileTable + "_source_attributes").is(":visible") && $("#" + xplanTable+ "_attributes_table").is(":visible")) {
    $("#sql_area").show();
  }
}

// ----------------------------------------------------------------------------------------------------
// WHERE-Filter
// ----------------------------------------------------------------------------------------------------
function filterEintragen() {
  // Zeigt die WHERE Sektion im SQL-Fenster
  $("#sql_where").show();
  // Filter unsichtbar, FilterLoeschen sichtbar
  $("#filter").hide();
  $("#filterloeschen").show();
  // Show Filter Zuweisung
  $("#where_zuweisung").show();
}

function filterLoeschen() {
  // Verbirgt die WHERE Sektion im SQL-Fenster
  $("#sql_where").html('<div id="sql_where"><b>WHERE</b></div>');
  $("#sql_where").hide();
  // Filter sichtbar, Filterloeschen Unsichtbar
  $("#filterloeschen").hide();
  $("#where_compare_value").html("");
  $("#filter").show();
  setFilterDefault();
}

function setFilterDefault() {
  $("#where_compare_value")[0].selectedIndex = "0";
  //whereValueText.value = whereValueText.defaultValue;
  $("#where_shape_attribut_attribut_selector")[0].selectedIndex = "0";
  $("#where_operator_selector")[0].selectedIndex = "0";
  $("#where_zuweisung").hide();
}

function getWhereOperator(selectedOperator) {
  // Shows or Hides like input field if the operator is selected

  var operator = selectedOperator.value;
  var whereCompareValue = $("#where_compare_value");
  var whereLikeWert = $("#where_like_eingabe");
  if(operator == "like") {
    //Hide selector, Show Input Field
    whereCompareValue.hide();
    whereLikeWert.show();
  } else {
    // Show Selector, Hide Input Field
    whereCompareValue.show();
    whereLikeWert.hide();
    whereLikeWert[0].value = whereLikeWert[0].defaultValue;
  }
}

function whereEintragen() {
  var shpAttribut = $("#where_shape_attribut_attribut_selector")[0].value;
  var operator = $("#where_operator_selector")[0].value;
  if(shpAttribut == "where_default_shape_attribut_select") {
    // Falls Default dann Benachrichtigung und break Funktion
    alert("Bitte wählen Sie zuerst ein Shape-Attribut aus!");
    return;
  }
  if(operator== "") {
    // Falls Default dann Benachrichtigung und break Funktion
    alert("Bitte wählen Sie zuerst einen Operator aus!");
    return;
  }
  if(operator == "like") {
    var wert = $("#where_like_eingabe")[0].value;
    $("#sql_where").append('<div id ="sql_where_' + shpAttribut + '">' + shpAttribut + ' ' + operator + " '%" + wert + "%'</div>");
  } else {
  // Wenn Operator LIKE, dann Texteingabefeld für LIKE Filter ermöglichen und Hide Auswahlfeld
  var wert = $("#whereDistinctShpWert")[0].value;
  // Wenn es sich um keine Nummer handelt, werden '' hinzugefügt
  if(!$.isNumeric($("#whereDistinctShpWert").val())) {
    var wert = "'" + $("#whereDistinctShpWert")[0].value + "'";
  } else {
    var wert = $("#whereDistinctShpWert")[0].value;
  }
  $("#sql_where").append('<div id ="sql_where_' + shpAttribut + '">' + shpAttribut + ' ' + operator + ' ' + wert + "</div>");
  }
  setFilterDefault();
  resetAttributzuweisungen();
}

function whereEintragenAnd() {
  var shpAttribut = $("#where_shape_attribut_attribut_selector")[0].value;
  var operator = $("#where_operator_selector")[0].value;
  if(shpAttribut == "where_default_shape_attribut_select") {
    // Falls Default dann Benachrichtigung und break Funktion
    alert("Bitte wählen Sie zuerst ein Shape-Attribut aus!");
    return;
  }
  if(operator== "") {
    // Falls Default dann Benachrichtigung und break Funktion
    alert("Bitte wählen Sie zuerst einen Operator aus!");
    return;
  }
  var wert = $("#whereDistinctShpWert")[0].value;
  // Wenn es sich um keine Nummer handelt, werden '' hinzugefügt
  if(!$.isNumeric($("#whereDistinctShpWert").val())) {
    var wert = "'" + $("#whereDistinctShpWert")[0].value + "'";
  } else {
    var wert = $("#whereDistinctShpWert")[0].value;
  }
  $("#sql_where").append('<div id ="sql_where_' + shpAttribut + '">' + shpAttribut + ' ' + operator + ' ' + wert + " AND <br></div>");
}

// ----------------------------------------------------------------------------------------------------
// Attribute
// ----------------------------------------------------------------------------------------------------
function addAttribut(clicked_id) {
  // Reset
  resetAttributzuweisungen();
  // Neue Attribute hinzufügen oder löschen
  var sliced_id = clicked_id.slice(4); //nimmt "add_attribute" und schneidet add_ ab
  if($("#sql_area")[0].style.display == "none") {
    alert("Es muss zuerst auch eine Shape-Datei ausgewählt werden!");
  } else {
    $("#zuweisung_xplan_attribut").html(sliced_id);
    $("#rule_area").show();
    $("#attribut_assignment_area").show();
    setSelectorVisibility();
  }
}

function removeAttribut(clicked_id) {
  var sliced_id = clicked_id.slice(7); //nimmt "remove_attribute" und schneidet remove_ ab
  var sql_xplanAttribut = $("#sql_" + sliced_id)[0];
  sql_xplanAttribut.parentNode.removeChild(sql_xplanAttribut);
  $("#add_" + sliced_id ).show();
  $("#remove_" + sliced_id ).hide();
  // Falls Wert in INSERT INTO existiert, wird er gelöscht
  var strInsertInto = $("#sql_insertinto_attributes").html();
  var loeschMich = sliced_id  + ", ";
  if(strInsertInto.indexOf(loeschMich) >= 0) {
    var neuerString = strInsertInto.replace(loeschMich, '');
    $("#sql_insertinto_attributes").html(neuerString);
  }
}

function showRemoveHideAdd(xplanAttributsent) {
  var xplanAttribut = xplanAttributsent;
  $("#add_" + xplanAttribut).hide();
  $("#remove_" + xplanAttribut).show();
  // if remove_rechtscharakter = sichtbar dann, Warnung Rechtscharakter entfernen
  if($("#remove_rechtscharakter").is(":visible")){
    $("#Warnung_3").hide();
    $("sqlvalidation").show();
  }
}

function setSelectorVisibility() {
  var xplanAttribut = $("#zuweisung_xplan_attribut").html();
  var wertedefinition = $("#wertedefinition_" + xplanAttribut).html();
  var codelists = [
    "xp_gesetzlichegrundlage",
    "rp_sonstgrenzetypen",
    "rp_generischesobjekttypen"
  ]
  var datatypes = [
    "xp_externereferenz",
    "xp_generattribut",
    "xp_hoehenangabe"
  ]
  $("#add_zuweisung").hide();
  $("#attribut_assignment_area_normal").show();
  $("#xp_externereferenz_area").hide();
  $("#xp_generattribut_area").hide();
  $("#xp_hoehenangabe_area").hide();
  $("#externe_codeliste").hide();
  if($("#wertespanne_" + xplanAttribut).html() == "[0..*]") {
    $("#add_zuweisung").show();
  }
  if($.inArray(wertedefinition,codelists) !== -1) {
    // Falls Codeliste
    $("#zuweisung_selector").hide();
    $("#externe_codeliste").show();
    $("#attribut_assignment_area_normal").hide();
  } else if ($.inArray(wertedefinition,datatypes) !== -1) {
    $("#attribut_assignment_area_normal").hide();
    // Falls Datatypes
    $("#zuweisung_selector").hide();
    if(wertedefinition == datatypes[0]) {
      //externereferenz
      $("#xp_externereferenz_area").show();
    }
    if(wertedefinition == datatypes[1]) {
      //generattribut
      $("#xp_generattribut_area").show();
    }
    if(wertedefinition == datatypes[2]) {
      //hoehenangabe
      $("#xp_hoehenangabe_area").show();
    }
  } else {
    // Falls Text, Integer, Boolean, Enumerationsliste etc. dann
    $("#zuweisung_selector").show();
  }
}

function zuweisungSelect(clicked_id) {
  var xplanAttribut = $("#zuweisung_xplan_attribut").html();
  //Wertedefinition ist z.B. bool, integer, text
  var wertedefinition = $("#wertedefinition_" + xplanAttribut).html();
  //Zeigt Plus für multiple Zuordnungen
  var zuweisung = $("#zuweisung_selector")[0].value;
  $("#zuweisung_selector").hide();
  // falls fester Wert dann
  if(zuweisung == "fester_wert_selector") {
    // beinhaltet auch Codelists
    if(wertedefinition == "text") {
      $("#fester_wert_text").show();
      $("#fester_wert_text_add").show();
      //falls array
      var wertespanneText = $("#wertespanne_" + xplanAttribut).html();
      if((wertespanneText == "[0..*]") || (wertespanneText == "[1..*]")) {
        $("#fester_wert_text_array_add").show();
      }
    }
    // falls boolean dann auswahlfeld ja nein?
    if(wertedefinition == "bool") {
      $("#fester_wert_boolean").show();
    }
    // falls integer dann auswahlfeld, dass nur Nummern zulässt
    if(wertedefinition == "int4") {
      $("#fester_wert_integer").show();
      $("#fester_wert_integer_add").show();
    }
    // falls Enumerationsliste dann Listenauswahl.
    if((wertedefinition != "text") && (wertedefinition != "bool") && (wertedefinition != "int4")) {
      //Auswahlliste mit AJAX abfragen und anzeigen
      getEnumerationListe();
    }
  }
  //falls alle aus Shapefile dann
  if(zuweisung == "alle_aus_shape_attribut_selector") {
    $("#attribut_assignment_area").show();
    $("#alle_aus_shape_attribut_attribut_selector").show();
  }
  // falls case dann
  if(zuweisung =="wenn_dann_selector") {
    if(wertedefinition == "text") {
      $("#wenn_dann_value_text_selector").show();
      $("#wenn_dann_text_add").show();
      //falls array
      var wertespanneWennDann = $("#wertespanne_" + xplanAttribut).html();
      if((wertespanneWennDann == "[0..*]") || (wertespanneWennDann== "[1..*]")) {
        $("#wenn_dann_text_array_add").show();
      }
    }
    if(wertedefinition == "bool") {
      $("#wenn_dann_value_boolean_selector").show();
      $("#wenn_dann_boolean_add").show();
    }
    if(wertedefinition == "int4") {
      $("#wenn_dann_value_integer_selector").show();
      $("#wenn_dann_integer_add").show();
    }
    if((wertedefinition != "text") && (wertedefinition != "bool") && (wertedefinition != "int4")) {
      //Auswahlliste mit AJAX abfragen und anzeigen
      getEnumerationListe2();
      $("#wenn_dann_enumeration_select").show();
      $("#wenn_dann_enumeration_add").show();
      $("#wenn_dann_enumeration_case_add").show();
      //falls array
     var wertespanneWennDann = $("#wertespanne_" + xplanAttribut).html();
      if((wertespanneWennDann == "[0..*]") || (wertespanneWennDann== "[1..*]")) {
        $("#wenn_dann_enumeration_array_add").show();
      }
    }
    $("#wenn_dann_operator_selector").show();
    $("#attribut_assignment_area").show();
    $("#wenn").show();
    $("#zuweisung_shp_attribute_wenn_dann").show();
    $("#wenn_dann").show();
    $("#wenn_dann_shape_attribut_attribut_selector").show();
  }
}

function insertIntoAttributes(xplanAttribut) {
  // Falls Wert noch nicht in INSERT INTO existiert, wird er eingetragen
  var str = $("#sql_insertinto_attributes").html();
  if(str.indexOf(xplanAttribut) <= 0) {
    $("#sql_insertinto_attributes").append(xplanAttribut + ", ");
  }
}  

// ----------------------------------------------------------------------------------------------------
// Wert Eintragen
// ----------------------------------------------------------------------------------------------------
function festenWertTextEintragen(clicked_id) {
  var xplanAttribut  = $("#zuweisung_xplan_attribut").html();
  var festerWert = $("#fester_wert_text")[0].value;
  var wertespanneAttribut = $("#wertespanne_" + xplanAttribut).html();
  if((wertespanneAttribut == "[0..*]") || (wertespanneAttribut == "[1..*]")) {
    //Ist Array
    var divAttribut = $("#sql_" + xplanAttribut);
    // Falls div existiert, wird replaced, falls nicht neu erstellt
    // Prüft ob div bereits existiert
    if (divAttribut.length) {
      // existiert
      var divAttributHtml = divAttribut.html();
      // REGEX um alle Werte zwischen [] innerhalb des Strings zu erhalten
      var result = divAttributHtml.match(/[^[\]]+(?=])/g);
      // Hier Anführungszeichen, da Text
      festerWert = result + ',' + "'" + festerWert + "'";
      //Ersetzt den Text
      divAttribut.replaceWith('<div id ="sql_' + xplanAttribut  + '">ARRAY[' + "" + festerWert + "]" + ' AS ' + xplanAttribut + ',</div>');
      $("#fester_wert_text")[0].value = $("#fester_wert_text")[0].defaultValue;
    } else {
      $("#sql_select").append('<div id="sql_' + xplanAttribut + '">ARRAY[' + "'" + festerWert + "'" + "] AS " + xplanAttribut + ',</div>');
      $("#fester_wert_text")[0].value = $("#fester_wert_text")[0].defaultValue;
    }
  } else {
    // Kein Array
    $("#sql_select").append('<div id ="sql_' + xplanAttribut  + '">' + "'" + festerWert + "'" + ' AS ' + xplanAttribut  + ',</div>');
  }
  
  if(clicked_id == "fester_wert_text_add") {
    insertIntoAttributes(xplanAttribut);
    resetAttributzuweisungen();
    showRemoveHideAdd(xplanAttribut);
  }
}

function festenWertEintragenInteger() {
  var xplanAttribut  = $("#zuweisung_xplan_attribut").html();
  var festerWertInteger = $("#fester_wert_integer")[0].value;
  $("#sql_select").append('<div id ="sql_' + xplanAttribut  + '">' + festerWertInteger.value + ' AS ' + xplanAttribut  + ',</div>');
  insertIntoAttributes(xplanAttribut);
  resetAttributzuweisungen();
  showRemoveHideAdd(xplanAttribut);
}

function festenWertEintragenBoolean() {
  var xplanAttribut  = $("#zuweisung_xplan_attribut").html();
  var festerWertBoolean = $("#fester_wert_boolean")[0].value;
  if(festerWertBoolean == 'true'){
    $("#sql_select").append('<div id ="sql_' + xplanAttribut  + '">' + "'t'" + ' AS ' + xplanAttribut  + ',</div>');
  }
  if(festerWertBoolean == 'false'){
   $("#sql_select").append('<div id ="sql_' + xplanAttribut  + '">' + "'f'" + ' AS ' + xplanAttribut  + ',</div>');
  }
  insertIntoAttributes(xplanAttribut);
  resetAttributzuweisungen();
  showRemoveHideAdd(xplanAttribut);
}

function festenWertEintragenEnumeration() {
  var xplanAttribut = $("#zuweisung_xplan_attribut").html();
  // zum Casten des Werts auf die Wertedefinition
  var wertedefinition = $("#wertedefinition_" + xplanAttribut).html();
  var festerWertEnumeration = $("#fester_wert_enumeration")[0].value;
  // REGEX um alle Werte nach dem letzten _ zu erhalten
  var festerWertEnumerationValue = /[^_]*$/.exec(festerWertEnumeration)[0];
  $("#sql_select").append('<div id="sql_' + xplanAttribut + '">' + "'" + festerWertEnumerationValue + "'::xplan_gml." + wertedefinition + ' AS ' + xplanAttribut + ',</div>');
  insertIntoAttributes(xplanAttribut);
  resetAttributzuweisungen();
  showRemoveHideAdd(xplanAttribut);
}

function festenWertEintragenEnumerationArray(clicked_id) {
  var xplanAttribut = $("#zuweisung_xplan_attribut").html();
  // zum Casten des Werts auf die Wertedefinition
  var wertedefinition = $("#wertedefinition_" + xplanAttribut).html();
  var festerWertEnumeration = $("#fester_wert_enumeration")[0].value;
  //REGEX um alle Werte nach dem letzten _ zu erhalten
  var festerWertEnumerationValue = /[^_]*$/.exec(festerWertEnumeration)[0];
  if(festerWertEnumeration == "") {
    // Falls Default dann Benachrichtigung und break Funktion
    alert("Bitte wählen Sie zuerst einen Enumerationswert aus!");
    return;
  }
  var divAttribut = $("#sql_" + xplanAttribut);
  // Falls div existiert, wird replaced, falls nicht neu erstellt
  // Prüft ob div bereits existiert
  if (divAttribut.length) {
    // existiert
    var divAttributHtml = divAttribut.html();
    // REGEX um alle Werte zwischen [] innerhalb des Strings zu erhalten
    var result = divAttributHtml.match(/[^[\]]+(?=])/g);
    festerWertEnumerationValue = result + ',' + festerWertEnumerationValue;
    //Ersetzt den Text
    divAttribut.replaceWith('<div id="sql_' + xplanAttribut + '">ARRAY[' + festerWertEnumerationValue + ']::xplan_gml.' + wertedefinition +  '[] AS ' + xplanAttribut + ',</div>');
  } else {
    //Hier im SQL Werte in Klammern [] setzen und, falls multiple, kommaseparieren
    $("#sql_select").append('<div id="sql_' + xplanAttribut + '">ARRAY[' + festerWertEnumerationValue + ']::xplan_gml.' + wertedefinition +  '[] AS ' + xplanAttribut + ',</div>');
  }
  // falls add ohne or, dann abschliessen, ansonsten weitere möglich
  if(clicked_id == "fester_wert_enumeration_add") {
    resetAttributzuweisungen();
    insertIntoAttributes(xplanAttribut);
    showRemoveHideAdd(xplanAttribut);
    showRemoveHideAdd(xplanAttribut);
  }
}

function alleAusShapeAttributEintragen() {
  // in SQL einfügen
  var shpAttribut = $("#alle_aus_shape_attribut_attribut_selector")[0].value;
  var xplanAttribut  = $("#zuweisung_xplan_attribut").html();
  $("#sql_select").append('<div id ="sql_' + xplanAttribut  + '">' + shpAttribut + ' AS ' + xplanAttribut  + ',</div>');
  insertIntoAttributes(xplanAttribut);
  resetAttributzuweisungen();
  showRemoveHideAdd(xplanAttribut);
}

function wennDannEintragen(clicked_id) {
  var xplanAttribut = $("#zuweisung_xplan_attribut").html();
  var wertedefinition = $("#wertedefinition_" + xplanAttribut).html();
  var wertespanneAttribut = $("#wertespanne_" + xplanAttribut).html();
  var shpAttribut = $("#wenn_dann_shape_attribut_attribut_selector")[0].value;
  var operator = $("#wenn_dann_operator_selector")[0].value;
  if(shpAttribut == "default_shape_attribut_select") {
    // Wenn Eingabe fehlt dann
    alert("Bitte wählen Sie zuerst ein Shape-Attribut aus!");
    return;
  }
  if(operator == "") {
    // Wenn Eingabe fehlt dann
    alert("Bitte wählen Sie zuerst einen Operator aus!");
    return;
  }
  var shpWert = $("#distinctShpWert")[0].value;
  // Text
  if(wertedefinition == "text") {
    var resultWert = "'" + $("#wenn_dann_value_text_selector")[0].value + "'";
    $("#sql_select").append('<div id ="sql_' + xplanAttribut + '">' + "CASE WHEN " + shpAttribut + ' ' + operator + ' ' + "'" + shpWert + "'" + " THEN " + " " + resultWert + " END AS " + xplanAttribut +  ',</div>');
  }
  // Int
  if(wertedefinition == "int4") {
    var resultWert = $("#wenn_dann_value_integer_selector")[0].value;
    $("#sql_select").append('<div id ="sql_' + xplanAttribut + '">' + "CASE WHEN " + shpAttribut + ' ' + operator + ' ' + "'" + shpWert + "'" + " THEN " + " " + resultWert + " END AS " + xplanAttribut +  ',</div>');
  }
  // Bool
  if(wertedefinition == "bool") {
    var resultWert = "'" + $("#wenn_dann_value_boolean_selector")[0].value + "'";
    if(resultWert == "''") {
      // Falls Default dann Benachrichtigung und break Funktion
      alert("Bitte wählen Sie zuerst einen Boolean-Wert aus!");
      return;
    }
    $("#sql_select").append('<div id ="sql_' + xplanAttribut + '">' + "CASE WHEN " + shpAttribut + ' ' + operator + ' ' + "'" + shpWert + "'" + " THEN " + " " + resultWert + " END AS " + xplanAttribut +  ',</div>');
  }
  // Enumeration
  if((wertedefinition != "text") && (wertedefinition != "bool") && (wertedefinition != "int4")) {
    var wennDannEnumeration = $("#wenn_dann_enumeration")[0].value;
    //REGEX um alle Werte nach dem letzten _ zu erhalten
    var resultWert = /[^_]*$/.exec(wennDannEnumeration)[0];
    if(wennDannEnumeration == "") {
      // Wenn Eingabe fehlt dann
      alert("Bitte wählen Sie zuerst einen Enumerationswert aus!");
      return;
    }
    var divAttribut = $("#sql_" + xplanAttribut);
    if((wertespanneAttribut == "[0..*]") || (wertespanneAttribut == "[1..*]")) {
      // Die Enumeration ist ein Array
      if(divAttribut.length) {
          
        // Das Div existiert bereits und ist ein Array
         if(clicked_id == "wenn_dann_enumeration_case_add") {
          //CASE
          // Nimmt den Inhalt des divAttributs und hängt einen weiteren Case an
          divAttribut.append("<br>CASE WHEN " +shpAttribut + ' ' + operator + ' ' + "'" + shpWert + "'" + " THEN ARRAY[" + resultWert + "]::xplan_gml." + wertedefinition + "[] END AS " + xplanAttribut + ',');
         }
        if(clicked_id == "wenn_dann_enumeration_array_add") {
          // Array ADD
          // Nimmt den letzten Wert zwischen [], hängt einen weiteren Wert an und wechselt den String aus
          var divAttributHtml = divAttribut.html();
          // REGEX um alle Werte zwischen dem letzen [] innerhalb des Strings zu erhalten
          var result = divAttributHtml.match(/[^[\]]+(?=])/g)
          // nimmt nur den letzten Wert des match Arrays, falls mehrere [] vorkommen
          var resultSliced = result.slice(-1)[0];
          var resultWert = resultSliced + "," + resultWert;
          // wechselt den letzten Wert
          var word = 'ARRAY[' + resultSliced + ']';
          var newWord = 'ARRAY[' + resultWert + ']';
          // Sucht die letzte Stelle, an der word genutzt wurde
          var n = divAttributHtml.lastIndexOf(word);
          // sliced den String durch 2, vom Start bis zum lastIndexOf
          // und wechselt word im Rest aus
          divAttributHtml = divAttributHtml.slice(0, n) + divAttributHtml.slice(n).replace(word, newWord);
          divAttribut.replaceWith('<div id ="sql_' + xplanAttribut + '">' + divAttributHtml +  ',</div>')
        }
        if(clicked_id == "wenn_dann_enumeration_add") {
          // Ende
          // Es wird kein Wert mehr eingetragen
        }
      } else {
        // Das Div existiert noch nicht und ist ein Array
        // falls noch kein Wert eingetragen ist, wird der gewählte Wert 1x eingetragen
        $("#sql_select").append('<div id ="sql_' + xplanAttribut + '">' + "CASE WHEN " + shpAttribut + ' ' + operator + ' ' + "'" + shpWert + "'" + " THEN ARRAY[" + resultWert + "]::xplan_gml." + wertedefinition+ "[] END AS " + xplanAttribut +  ',</div>');
      }
    } else {
      // Die Enumeration ist kein Array
      if(divAttribut.length) {
      // Das Div existiert bereits und ist kein Array
        if(clicked_id == "wenn_dann_enumeration_case_add") {
          // CASE
          // Nimmt den Inhalt des divAttributs und hängt einen weiteren Case an
          $('#sql_' + xplanAttribut).append("<br>CASE WHEN " +shpAttribut + ' ' + operator + ' ' + "'" + shpWert + "'" + " THEN " + resultWert + "::" + wertedefinition + " END AS " + xplanAttribut + ',');
        }
        if(clicked_id == "wenn_dann_enumeration_add") {
          // Ende
          // Es wird kein Wert mehr eingetragen
        }
      } else {
        // das Div existiert noch nicht und ist kein Array
        // Der gewählte Wert wird 1x eingetragen
        $("#sql_select").append('<div id ="sql_' + xplanAttribut + '">' + "CASE WHEN " + shpAttribut + ' ' + operator + ' ' + "'" + shpWert + "'" + " THEN " + resultWert + "::xplan_gml." + wertedefinition +  "END AS " + xplanAttribut +  ',</div>');
      }
    }
  }
  if((clicked_id == "wenn_dann_enumeration_add") ||(clicked_id == "wenn_dann_boolean_add") || (clicked_id == "wenn_dann_integer_add") || (clicked_id == "wenn_dann_text_add")) {
    insertIntoAttributes(xplanAttribut);
    resetAttributzuweisungen();
    showRemoveHideAdd(xplanAttribut);
  }
}

function externeCodelisteEintragen(clicked_id) {
  var xplanAttribut  = $("#zuweisung_xplan_attribut").html();
  var quelle= $("#externe_codeliste_quelle")[0].value;
  var wert = $("#externe_codeliste_wert")[0].value;
  $("#sql_select").append('<div id ="sql_' + xplanAttribut  + '">' + "ROW('" + quelle + "','" + wert + "')" + ' AS ' + xplanAttribut  + ',</div>');
  if(clicked_id == "xp_generattribut_add") {
    insertIntoAttributes(xplanAttribut);
    resetAttributzuweisungen();
    showRemoveHideAdd(xplanAttribut);
  }
}

function hoehenangabeAdd(clicked_id) {
  var xplanAttribut  = $("#zuweisung_xplan_attribut").html();
  var abweichenderHoehenbezug = "'" + $("#xp_hoehenangabe_abweichenderHoehenbezug")[0].value + "'";
  if(abweichenderHoehenbezug == "''") {abweichenderHoehenbezug = 'NULL';}
  var hoehenbezug = "'" + $("#xp_hoehenangabe_hoehenbezug")[0].value + "'";
  if(hoehenbezug == "''") {hoehenbezug = 'NULL';}
  var bezugspunkt = "'" + $("#xp_hoehenangabe_bezugspunkt")[0].value + "'";
  if(bezugspunkt == "''") {bezugspunkt = 'NULL';}
  var hMin = "'" + $("#xp_hoehenangabe_hmin")[0].value + "'";
  if(hMin == "''") {hMin = 'NULL';}
  var hMax = "'" + $("#xp_hoehenangabe_hmax")[0].value + "'";
  if(hMax == "''") {hMax = 'NULL';}
  var hZwingend = "'" + $("#xp_hoehenangabe_hzwingend")[0].value + "'";
  if(hZwingend == "''") {hZwingend = 'NULL';}
  var h = "'" + $("#xp_hoehenangabe_h")[0].value + "'";
  if(h == "''") {h = 'NULL';}
  var divAttribut = $("#sql_" + xplanAttribut);
  if(divAttribut.length) {
    //existiert bereits
    var divAttributHtml = divAttribut.html();
    // REGEX um alle Werte zwischen [] innerhalb des Strings zu erhalten
    var result = divAttributHtml.match(/[^[\]]+(?=])/g);
    divAttribut.replaceWith('<div id ="sql_' + xplanAttribut  + '">' + "ARRAY[" + result + ",ROW(" +abweichenderHoehenbezug + "," + hoehenbezug + "," + bezugspunkt + "," + hMin + "," + hMax + "," + hZwingend + "," + h + ")]::xplan_gml.xp_hoehenangabe[]" + ' AS ' + xplanAttribut  + ',</div>');
  } else {
    $("#sql_select").append('<div id ="sql_' + xplanAttribut  + '">' + "ARRAY[ROW(" +abweichenderHoehenbezug + "," + hoehenbezug + "," + bezugspunkt + "," + hMin + "," + hMax + "," + hZwingend + "," + h + ")]::xplan_gml.xp_hoehenangabe[]" + ' AS ' + xplanAttribut  + ',</div>');
  }
  if(clicked_id == "xp_hoehenangabe_add") {
    insertIntoAttributes(xplanAttribut);
    resetAttributzuweisungen();
    showRemoveHideAdd(xplanAttribut);
  }
}

function externeReferenzAdd(clicked_id) {
  // Muss immer Array sein
  var xplanAttribut  = $("#zuweisung_xplan_attribut").html();
  var georefURL = "'" + $("#xp_externereferenz_georefurl")[0].value + "'";
  if(georefURL == "''") {georefURL = 'NULL';}
  var georefMimeType = "'" + $("#xp_externereferenz_georefmimetype")[0].value + "'";
  if(georefMimeType == "''") {georefMimeType = 'NULL';}
  var art = "'" + $("#xp_externereferenz_art")[0].value + "'"
  if(art == "''") {georefMimeType = 'NULL';}
  var informationssystemURL = "'" + $("#xp_externereferenz_informationssystemurl")[0].value + "'";
  if(informationssystemURL == "''") {informationssystemURL = 'NULL';}
  var referenzName = "'" + $("#xp_externereferenz_referenzname")[0].value + "'";
  if(referenzName == "''") {referenzName = 'NULL';}
  var referenzURL = "'" + $("#xp_externereferenz_referenzurl")[0].value + "'";
  if(referenzURL == "''") {referenzURL = 'NULL';}
  var referenzMimeType = "'" + $("#xp_externereferenz_referenzmimetype")[0].value + "'";
  if(referenzMimeType == "''") {referenzMimeType = 'NULL';}
  var beschreibung = "'" + $("#xp_externereferenz_beschreibung")[0].value + "'";
  if(beschreibung == "''") {beschreibung = 'NULL';}
  var datum = "'" + $("#xp_externereferenz_datum")[0].value + "'";
  if(datum == "''") {datum = 'NULL';}
  var divAttribut = $("#sql_" + xplanAttribut);
  if(divAttribut.length) {
    //existiert bereits
    var divAttributHtml = divAttribut.html();
    // REGEX um alle Werte zwischen [] innerhalb des Strings zu erhalten
    var result = divAttributHtml.match(/[^[\]]+(?=])/g);
    divAttribut.replaceWith('<div id ="sql_' + xplanAttribut  + '">' + "ARRAY[" + result + ",ROW(" + georefURL + "," + georefMimeType + "," + art + "," + informationssystemURL + "," + referenzName + "," + referenzURL + "," + referenzMimeType + "," + beschreibung + "," + datum + ")]::xplan_gml.xp_externereferenz[]" + ' AS ' + xplanAttribut  + ',</div>');
  } else {
    //existiert noch nicht
     $("#sql_select").append('<div id ="sql_' + xplanAttribut  + '">' + "ARRAY[ROW(" + georefURL + "," + georefMimeType + "," + art + "," + informationssystemURL + "," + referenzName + "," + referenzURL + "," + referenzMimeType + "," + beschreibung + "," + datum + ")]::xplan_gml.xp_externereferenz[]" + ' AS ' + xplanAttribut  + ',</div>');
  }
  if(clicked_id == "xp_externereferenz_add") {
    insertIntoAttributes(xplanAttribut);
    resetAttributzuweisungen();
    showRemoveHideAdd(xplanAttribut);
  }
}

function generAttributAdd(clicked_id) {
  if($("#xp_generattribut_name")[0].value.length > 0) {
    var xplanAttribut  = $("#zuweisung_xplan_attribut").html();
    var name= $("#xp_generattribut_name")[0].value;
    var divAttribut = $("#sql_" + xplanAttribut);
    if(divAttribut.length) {
      //existiert bereits
      var divAttributHtml = divAttribut.html();
      // REGEX um alle Werte zwischen [] innerhalb des Strings zu erhalten
       var result = divAttributHtml.match(/[^[\]]+(?=])/g);
      divAttribut.replaceWith('<div id ="sql_' + xplanAttribut  + '">' + "ARRAY[" + result + ",ROW('" + name + "')]::xplan_gml.xp_generattribut[]" + ' AS ' + xplanAttribut  + ',</div>');
    } else {
      // existiert noch nicht
      $("#sql_select").append('<div id ="sql_' + xplanAttribut  + '">' + "ARRAY[ROW('" + name + "')]::xplan_gml.xp_generattribut[]" + ' AS ' + xplanAttribut  + ',</div>');
    }
    if(clicked_id == "xp_generattribut_add") {
      insertIntoAttributes(xplanAttribut);
      resetAttributzuweisungen();
      showRemoveHideAdd(xplanAttribut);
    }
  } else {
    alert("Das Feld Name ist Pflicht beim Einfügen eines neuen generischen Attributs!");
  }
}

// ----------------------------------------------------------------------------------------------------
// RESET Funktionen
// ----------------------------------------------------------------------------------------------------
function resetAttributzuweisungen() {
  //Stellt Defautleinstellung für Attributzuweisungen wieder her
  $("#rule_area").hide();
  $("#attribut_assignment_area").hide();
  $("#zuweisung_selector").show();
  $("#zuweisung_selector")[0].selectedIndex = "0";
  // Reset fester Wert + festerWert Integer
  $("#fester_wert_text_add").hide();
  $("#fester_wert_text_array_add").hide();
  var festerWert =  $("#fester_wert_text");
  festerWert.hide();
  festerWert[0].value = festerWert[0].defaultValue;
  $("#fester_wert_integer_add").hide();
  var festerWertInteger =  $("#fester_wert_integer");
  festerWertInteger.hide();
  festerWertInteger[0].value = festerWert[0].defaultValue;
  $("#alle_aus_shape_attribut_attribut_selector").hide();
  // Reset fester Wert Boolean
  $("#fester_wert_boolean").hide();
  $("#fester_wert_boolean")[0].selectedIndex = "0";
  // Reset fester Wert Enumerationsliste
  $("#enumeration_select").html("");
  // Reset alle aus Shapefile
  $("#alle_aus_shape_attribut_attribut_selector").hide();
  // Reset Wenn Dann
  // Prüft ob id-Element existiert
  if($("#wenn_dann_shape_attribut_attribut_selector").length){
    $("#wenn_dann_shape_attribut_attribut_selector").hide();
    $("#wenn_dann_shape_attribut_attribut_selector")[0].selectedIndex = "0";
  }
  $("#wenn_dann_compare_value").html("");
  var wennDannValueTextSelector = $("#wenn_dann_value_text_selector");
  wennDannValueTextSelector[0].value = wennDannValueTextSelector[0].defaultValue;
  wennDannValueTextSelector.html("");
  wennDannValueTextSelector.hide();
  $("#wenn_dann_text_add").hide();
  var wennDannValueIntSelector = $("#wenn_dann_value_integer_selector");
  wennDannValueIntSelector[0].value = wennDannValueIntSelector[0].defaultValue;
  wennDannValueIntSelector.html("");
  $(wennDannValueIntSelector).hide();
  $("#wenn_dann_integer_add").hide();
  $("#wenn_dann_value_boolean_selector")[0].selectedIndex = "0";
  $("#wenn_dann_value_boolean_selector").hide();
  $("#wenn_dann_boolean_add").hide();
  $("#wenn_dann_enumeration_select").hide();
  $("#wenn_dann_enumeration_add").hide();
  $("#wenn_dann_enumeration_case_add").hide();
  $("#wenn_dann_enumeration_array_add").hide();
  $("#fester_wert_enumeration").html("");
  $("#wenn_dann_enumeration").html("");
  $("#wenn_dann_operator_selector").hide();
  $("#wenn_dann_operator_selector")[0].selectedIndex = "0";
  $("#zuweisung_shp_attribute_wenn_dann").html("");
  $("#wenn_dann").hide();
  $("#wenn").hide();
  // Reset Codelisten
  var quelle = $("#externe_codeliste_quelle");
  quelle[0].value = quelle[0].defaultValue;
  var wert = $("#externe_codeliste_wert");
  wert[0].value = wert[0].defaultValue;
  $("#externe_codeliste").hide();
  getShapeAttributes2();
  // Reset komplexe Werte
  $("#xp_externereferenz_area").hide();
  var exRefgeorefURL = $("#xp_externereferenz_georefurl");
  var exRefGeoRefMimeType = $("#xp_externereferenz_georefmimetype");
  var exRefArt = $("#xp_externereferenz_art");
  var exRefInformationssystemURL = $("#xp_externereferenz_informationssystemurl");
  var exRefReferenzName = $("#xp_externereferenz_referenzname");
  var exRefReferenzURL = $("#xp_externereferenz_referenzurl");
  var exRefReferenzMimeType = $("#xp_externereferenz_referenzmimetype");
  var exRefBeschreibung = $("#xp_externereferenz_beschreibung");
  var exRefDatum =  $("#xp_externereferenz_datum");
  exRefgeorefURL[0].value = exRefgeorefURL[0].defaultValue;
  exRefGeoRefMimeType[0].selectedIndex = "0";
  exRefArt[0].selectedIndex = "0";
  exRefInformationssystemURL[0].value = exRefInformationssystemURL[0].defaultValue;
  exRefReferenzName[0].value = exRefReferenzName[0].defaultValue;
  exRefReferenzURL[0].value = exRefReferenzURL[0].defaultValue;
  exRefReferenzMimeType[0].selectedIndex = "0";
  exRefBeschreibung[0].value = exRefReferenzURL[0].defaultValue;
  exRefDatum[0].value = exRefReferenzURL[0].defaultValue;
  $("#xp_generattribut_area").hide();
  var generAttributName = $("#xp_generattribut_name");
  generAttributName[0].value = generAttributName[0].defaultValue;
  $("#xp_hoehenangabe_area").hide();
  // Reset multiple Werte Plus
  $("#add_zuweisung").hide();
}

// ----------------------------------------------------------------------------------------------------
// AJAX Funktionen
// ----------------------------------------------------------------------------------------------------
function getXPlanAttributes() {
  console.log('getXPlanAttributes');
   var ajaxRequest; // The variable that makes Ajax possible!
   try{
      ajaxRequest = new XMLHttpRequest(); // Opera 8.0+, Firefox, Safari
   }catch (e){
      try{  // Internet Explorer Browsers
         ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
      }catch (e) {
                     try{
            ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
         }catch (e){ // Fehler
            alert("Your browser broke!");
            return false;
         }
      }
   }
    // Funktion die Daten, die vom Server gesendet wurden erhält und
   // die Div Sektion dieser Seite updated
  ajaxRequest.onreadystatechange = function() {
    if(ajaxRequest.readyState == 4){
      var ajaxDisplay = document.getElementById('target_table');
      ajaxDisplay.innerHTML = ajaxRequest.responseText;
      // SQL-Area wird hier nocheinmal aufgerufen (redundant?)
      showSqlArea();
    }
  }
   // Nimmt den Wert featuretype und gibt es an den Server Script
   var featuretype = document.getElementById('target_selector').value;
   var queryString = "?featuretype=" + featuretype;
   ajaxRequest.open("GET", getRelPath() + "ajax-getxplanattributes.php" + queryString, true);
   ajaxRequest.send(null);
}

function getShapeAttributes() {
   var ajaxRequest; // The variable that makes Ajax possible!
   try{
      ajaxRequest = new XMLHttpRequest(); // Opera 8.0+, Firefox, Safari
   }catch (e){
      try{  // Internet Explorer Browsers
         ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
      }catch (e) {
                     try{
            ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
         }catch (e){ // Fehler
            alert("Your browser broke!");
            return false;
         }
      }
   }
   // Funktion die Data, die vom Server gesendet wurde erhält und
   // die Div Sektion dieser Seite updated
  ajaxRequest.onreadystatechange = function() {
    if(ajaxRequest.readyState == 4){
      var ajaxDisplay = document.getElementById('zuweisung_shp_attribute');
      ajaxDisplay.innerHTML = ajaxRequest.responseText;
    }
  }
  // Nimmt den Wert Shapefile und gibt es an den Server Script 
   var shapefile = document.getElementById('source_selector').value;
   var queryString = "?shapefile=" + shapefile + "&konvertierung_id=" + konvertierung_id;
   ajaxRequest.open("GET", getRelPath() + "ajax-getshapeattributes.php" + queryString, true);
   ajaxRequest.send(null);
}

function getShapeAttributes2() {
   var ajaxRequest; // The variable that makes Ajax possible!
   try{  // Opera 8.0+, Firefox, Safari
      ajaxRequest = new XMLHttpRequest();
   }catch (e){ // Internet Explorer Browsers
      try{
         ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
      }catch (e) { 
         try{
            ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
         }catch (e){ // Fehler
            alert("Your browser broke!");
            return false;
         }
      }
   }
   // Funktion die Daten, die vom Server gesendet wurden erhält und
   // die Div Sektion dieser Seite updated
  ajaxRequest.onreadystatechange = function() {
    if(ajaxRequest.readyState == 4){
      var ajaxDisplay = document.getElementById('zuweisung_shp_attribute_wenn_dann');
      ajaxDisplay.innerHTML = ajaxRequest.responseText;
    }
  }
   // Nimm den Wert shapefile und gib es an den Server Script
   var shapefile = document.getElementById('source_selector').value;
   var queryString = "?shapefile=" + shapefile + "&konvertierung_id=" + konvertierung_id;
   ajaxRequest.open("GET", getRelPath() + "ajax-getshapeattributes2.php" + queryString, true);
   ajaxRequest.send(null);
}

function getShapeAttributes3() {
   var ajaxRequest; // The variable that makes Ajax possible!
   try{  // Opera 8.0+, Firefox, Safari
      ajaxRequest = new XMLHttpRequest();
   }catch (e){ // Internet Explorer Browsers
      try{
         ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
      }catch (e) { 
         try{
            ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
         }catch (e){ // Fehler
            alert("Your browser broke!");
            return false;
         }
      }
   }
   // Funktion die Daten, die vom Server gesendet wurden erhält und
   // die Div Sektion dieser Seite updated
  ajaxRequest.onreadystatechange = function() {
    if(ajaxRequest.readyState == 4){
      var ajaxDisplay = document.getElementById('where_zuweisung_shp_attribute');
      ajaxDisplay.innerHTML = ajaxRequest.responseText;
    }
  }
   // Nimm den Wert shapefile und gib es an den Server Script
   var shapefile = document.getElementById('source_selector').value;
   var queryString = "?shapefile=" + shapefile + "&konvertierung_id=" + konvertierung_id;
   ajaxRequest.open("GET", getRelPath() + "ajax-getshapeattributes3.php" + queryString, true);
   ajaxRequest.send(null);
}

function getShapeAttributeDistinct() {
  // Bricht den AJAX-Request ab, wenn Default-Wert ausgewählt wird
  var shpAttribut = document.getElementById('wenn_dann_shape_attribut_attribut_selector').value;
  if(shpAttribut == "default_shape_attribut_select") {
    $("#wenn_dann_compare_value").html(""); //  Leert Compare-Value, da dieser nur bei ausgewähltem Attribut relevant ist.
    return;
  }
  var ajaxRequest; // The variable that makes Ajax possible!
  try{  // Opera 8.0+, Firefox, Safari
    ajaxRequest = new XMLHttpRequest();
  }catch (e){ // Internet Explorer Browsers
    try{
      ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
    }catch (e) { 
       try{
        ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
       }catch (e){ // Fehler
          alert("Your browser broke!");
          return false;
        }
      }
}
   // Funktion die Daten, die vom Server gesendet wurden erhält und
   // die Div Sektion dieser Seite updated
  ajaxRequest.onreadystatechange = function() {
    if(ajaxRequest.readyState == 4){
      var ajaxDisplay = document.getElementById('wenn_dann_compare_value');
      ajaxDisplay.innerHTML = ajaxRequest.responseText;
    }
  }
   // Nimm den Wert shapefile und gib es an den Server Script
   var shapefile = document.getElementById('source_selector').value;
    var shapefileAttribut = document.getElementById('wenn_dann_shape_attribut_attribut_selector').value;
   var queryString = "?shapefile=" + shapefile + "&"  + "shapefile_attribut=" + shapefileAttribut  + "&konvertierung_id=" + konvertierung_id;
   ajaxRequest.open("GET", getRelPath() + "ajax-getshapeattributesdistinctvalues.php" + queryString, true);
   ajaxRequest.send(null);
}

function getShapeAttributeDistinct2() {
  // Bricht den AJAX-Request ab, wenn Default-Wert ausgewählt wird
  var shpAttribut = document.getElementById('where_shape_attribut_attribut_selector').value;
  if(shpAttribut == "where_default_shape_attribut_select") {
    $("#where_compare_value").html(""); //  Leert Compare-Value, da dieser nur bei ausgewähltem Attribut relevant ist.
    return;
  }
   var ajaxRequest; // The variable that makes Ajax possible!
   try{  // Opera 8.0+, Firefox, Safari
      ajaxRequest = new XMLHttpRequest();
   }catch (e){ // Internet Explorer Browsers
      try{
         ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
      }catch (e) { 
         try{
            ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
         }catch (e){ // Fehler
            alert("Your browser broke!");
            return false;
         }
      }
   }
   // Funktion die Daten, die vom Server gesendet wurden erhält und
   // die Div Sektion dieser Seite updated
  ajaxRequest.onreadystatechange = function() {
    if(ajaxRequest.readyState == 4){
      var ajaxDisplay = document.getElementById('where_compare_value');
      ajaxDisplay.innerHTML = ajaxRequest.responseText;
    }
  }
   // Nimm den Wert shapefile und gib es an den Server Script
   var shapefile = document.getElementById('source_selector').value;
   var shapefileAttribut = document.getElementById('where_shape_attribut_attribut_selector').value;
   var queryString = "?shapefile=" + shapefile + "&"  + "shapefile_attribut=" + shapefileAttribut + "&konvertierung_id=" + konvertierung_id;
   ajaxRequest.open("GET", getRelPath() + "ajax-getshapeattributesdistinctvalues2.php" + queryString, true);
   ajaxRequest.send(null);
}

function getEnumerationListe() {
   var ajaxRequest; // The variable that makes Ajax possible!
   try{  // Opera 8.0+, Firefox, Safari
      ajaxRequest = new XMLHttpRequest();
   }catch (e){ // Internet Explorer Browsers
      try{
         ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
      }catch (e) {
         try{
            ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
         }catch (e){ // Fehler
            alert("Your browser broke!");
            return false;
         }
      }
   }
   // Funktion die Daten, die vom Server gesendet wurden erhält und
   // die Div Sektion dieser Seite updated
  ajaxRequest.onreadystatechange = function() {
    if(ajaxRequest.readyState == 4){
      var ajaxDisplay = document.getElementById('enumeration_select');
      ajaxDisplay.innerHTML = ajaxRequest.responseText;
    }
  }
  // Nimm den Wert des FeatureTypes und den Wert des Xplan-Attributs und gib es an den Server Script
  var featuretype = document.getElementById('target_selector').value;
  var xplanAttribut = document.getElementById("zuweisung_xplan_attribut").innerHTML;
  var queryString = "?featuretype=" + featuretype + "&"  + "xplanattribut=" + xplanAttribut + "&konvertierung_id=" + konvertierung_id;
  ajaxRequest.open("GET", getRelPath() + "ajax-getxplanenumerationattributes.php" + queryString, true);
  ajaxRequest.send(null);
}

function getEnumerationListe2() {
   var ajaxRequest; // The variable that makes Ajax possible!
   try{  // Opera 8.0+, Firefox, Safari
      ajaxRequest = new XMLHttpRequest();
   }catch (e){ // Internet Explorer Browsers
      try{
         ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
      }catch (e) {
         try{
            ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
         }catch (e){ // Fehler
            alert("Your browser broke!");
            return false;
         }
      }
   }
   // Funktion die Daten, die vom Server gesendet wurden erhält und
   // die Div Sektion dieser Seite updated
  ajaxRequest.onreadystatechange = function() {
    if(ajaxRequest.readyState == 4){
      var ajaxDisplay = document.getElementById('wenn_dann_enumeration_select');
      ajaxDisplay.innerHTML = ajaxRequest.responseText;
    }
  }
  // Nimm den Wert des FeatureTypes und den Wert des Xplan-Attributs und gib es an den Server Script
  var featuretype = document.getElementById('target_selector').value;
  var xplanAttribut = document.getElementById("zuweisung_xplan_attribut").innerHTML;
  var queryString = "?featuretype=" + featuretype + "&"  + "xplanattribut=" + xplanAttribut + "&konvertierung_id=" + konvertierung_id;
  ajaxRequest.open("GET", getRelPath() + "ajax-getxplanenumerationattributes2.php" + queryString, true);
  ajaxRequest.send(null);
}