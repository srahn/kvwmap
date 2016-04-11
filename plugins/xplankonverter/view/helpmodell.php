<?php
?>
<script type="text/javascript" language="JavaScript">
  function HideContent(d) {
  document.getElementById(d).style.display = "none";
  }
  function ShowContent(d) {
  document.getElementById(d).style.display = "block";
  }
  function ReverseDisplay(d) {
  if(document.getElementById(d).style.display == "none") { document.getElementById(d).style.display = "block"; }
  else { document.getElementById(d).style.display = "none"; }
  }
  </script>
	<a href="javascript:ReverseDisplay('helpplaene')" class=hlink title="Erklärung der Tabellenfunktionen">
		<img src="plugins/xplankonverter/images/ICONhelp.png" alt="Hilfe Plaene" class = "help-image">
  </a>
  <div id="helpplaene" style="display:none;">
		<p>
      <img src="plugins/xplankonverter/images/help/RP_BodenschutzBeispiel.png"><br> 
        Das Modell wird in der Unified Modeling Language (UML) graphisch als Klassendiagramm dargestellt. Eine Klasse enthält einen Namen und
        gegebenenfalls ein Schlüsselwort (z.B. FeatureType) und Attribute (z.B. gebietsTyp).<br>
        Klassen leiten per Klick auf ihre Beschreibung im textlichen Modell in XPlan-Elemente oder Codelisten weiter.<br>
        Attribute können als [0..1] (eine Klasse kann 0 oder 1 Attribut dieses Typs besitzen),<br>
        [0..*] (eine Klasse kann 0 bis unendlich viele Attribute dieses Typs besitzen),<br>
        [1] bzw. keine Beziehungsanzahlanzeige (eine Klasse muss dieses Attribut genau einmal beinhalten) und<br>
        [1..*] (eine Klasse muss mindestens 1 Attribut dieses Typs besitzen und kann unendlich viele Attribute dieses Typs besitzen) vorkommen.<br><br>   
      <img src="plugins/xplankonverter/images/help/farbe1.png"> Diese Farbe stellt einen FeatureType des Pakets dar.<br>
      <img src="plugins/xplankonverter/images/help/farbe2.png"> Diese Farbe stellt eine Enumeration dar.<br>
      <img src="plugins/xplankonverter/images/help/farbe3.png"> Diese Farbe stellt einen FeatureType eines fremden Pakets dar, welches in Bezug zu diesem Paket steht (z.B. bei Ableitungen).<br>
      <img src="plugins/xplankonverter/images/help/farbe4.png"> Diese Farbe stellt eine Union dar (kann nicht angeklickt werden).<br>
      <!--<img src="plugins/xplankonverter/images/help/kommentar.png"> Diese Farbe stellt einen Kommentar dar. Dieser ist im eigentlichen Modell nicht enthalten, erläutert aber die Änderungsschritte gegenüber früheren Versionen.<br> -->
      <br>
      <img src="plugins/xplankonverter/images/help/ableitung.png"> Dieser Pfeil stellt eine Ableitung dar. Ein abgeleitetes Objekt enthält alle zusätzlich zu seinen Attributen alle Attribute des Originalobjekts.<br>
      <img src="plugins/xplankonverter/images/help/assoziation.png"> Dieser Pfeil stellt eine Assoziation dar. Assoziierte Objekte stehen miteinander in Verbindung (z.B. 0..1).<br><br>
      </p>
  </div>
<?php
?>