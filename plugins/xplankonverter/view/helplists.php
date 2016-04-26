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
		<img src="images/ICONhelp.png" alt="Hilfe Plaene" class = "help-image">
  </a>
  </h1>
  <div id="helpplaene" style="display:none;">
		<p>
			<img src="images/plus.png" width="13"> <img src="images/minus.png" width="13"><br>
				Klicken der Plus- oder Minus-Felder maximiert oder minimiert ein gesamtes Paket.<br><br>
			<img src="images/minimize.png" width="13"> <img src="images/maximize.png" width="13"><br>
				Klicken der Listen-Icons maximiert oder minimiert alle Listen oder eine Liste innerhalb eines Pakets.<br><br>
			<img src="images/aenderung.png" height="13"> <img src="images/veraltet.png" height="13"> <img src="images/neu.png" height="13"><br>
				"Änderungen" markieren veränderte Elemente im erweiterten Modell im Vergleich zu XPlanGML 4.1.<br>
        "Veraltet" markiert veraltete Elemente im Vergleich zu XPlanGML 4.1, welche im erweiterten RP_Modell nicht mehr verwendet werden. Diese sind gleichzeitig auch durchgestrichen.<br>
        "Neu" markiert neue Elemente des erweiterten Modells, welche in XPlanGML 4.1 nicht vorkommen.<br><br>
      <img src="images/Link.png" height="13"><br>
        Dieses Icon öffnet einen Link zum Element im XPlanung Wiki (nicht für Projektänderungen möglich)
		</p>
  </div>
  <h1>
<?php
?>