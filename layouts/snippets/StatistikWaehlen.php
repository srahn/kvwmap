<SCRIPT src="funktionen/tooltip.js" language="JavaScript"  type="text/javascript"></SCRIPT>
<DIV id="TipLayer" style="visibility:hidden;position:absolute;z-index:1000;top:-100"></DIV>
<script type="text/javascript">
Text[1]=["Tipp:","Durch die Auswahl zwischen Stelle und Nutzer kann die Statistik so angepasst werden, dass die Zugriffe pro Layer und Stelle oder die Zugriffe pro Layer und Nutzer angezeigt werden können. Wählt man hierbei Stelle und Nutzer aus, werden die Zugriffe pro Layer durch den Nutzer über eine bestimmt Stelle angezeigt."]
Text[2]=["Tipp:","An dieser Stelle kann die graphische Ausgabe der Zugriffe in einem entsprechenden Diagramm gewählt werden."]

function display(id) {
  if (id=="tablemonth") {
	document.getElementById("tablemonth").style.display="block";
	document.getElementById("tableweek").style.display="none";
	document.getElementById("tableday").style.display="none";
	document.getElementById("tableera").style.display="none";
  }
  if (id=="tableweek") {
	document.getElementById("tablemonth").style.display="none";
	document.getElementById("tableweek").style.display="block";
	document.getElementById("tableday").style.display="none";
	document.getElementById("tableera").style.display="none";
  }
  if (id=="tableday") {
	document.getElementById("tablemonth").style.display="none";
	document.getElementById("tableweek").style.display="none";
	document.getElementById("tableday").style.display="block";
	document.getElementById("tableera").style.display="none";
  }
  if (id=="tableera") {
	document.getElementById("tablemonth").style.display="none";
	document.getElementById("tableweek").style.display="none";
	document.getElementById("tableday").style.display="none";
	document.getElementById("tableera").style.display="block";
  }
}

</script>
<br>
<strong><font size="+2"><?php echo $this->titel; ?></font></strong>
<br><br>
<table align="center" border="0" cellspacing="0" cellpadding="0" rules="groups">
  <tr align="center">
    <td align="center"><font>&nbsp;Monat &nbsp;</font></a></td>
    <td align="center"><font>&nbsp;Woche &nbsp;</font></a></td>
    <td align="center"><font>&nbsp; Tag &nbsp;</font></a> </td>
    <td align="center"><font>&nbsp; Zeitraum &nbsp;</font></a></td>
  </tr>
  <tr align="center">
    <td align="center"><input type="radio" name="zeitraum" value="month" <?php if ($this->formvars['zeitraum']=='month' OR $this->formvars['zeitraum']=='' ) { ?> checked<?php } ?> onClick="javascript:display('tablemonth')"></td>
    <td align="center"><input type="radio" name="zeitraum" value="week" <?php if ($this->formvars['zeitraum']=='week') { ?> checked<?php } ?> onClick="javascript:display('tableweek')"></td>
    <td align="center"><input type="radio" name="zeitraum" value="day" <?php if ($this->formvars['zeitraum']=='day') { ?> checked<?php } ?> onClick="javascript:display('tableday')"></td>
    <td align="center"><input type="radio" name="zeitraum" value="era" <?php if ($this->formvars['zeitraum']=='era') { ?> checked<?php } ?> onClick="javascript:display('tableera')"></td>
  </tr>
</table>
<br><br>
<table align="center" border="1" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" rules="groups">
  <tr align="center"> 
    <td align="center"> <table align="center" id="tablemonth" style="display: <?php if ($this->formvars['zeitraum']=='month' OR $this->formvars['zeitraum']==''){ echo 'block';} else { echo 'none';} ?>" width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr> 
          <th width="71%">Monat:</th>
          <th width="29%"><strong>Jahr:</strong></th>
        </tr>
        <tr> 
          <td align="center"><select name="month_m">
              <option value="">...bitte wählen</option>
              <option value="1" <?php if ($this->formvars['month_m']=='1') { ?> selected<?php } ?>>Januar</option>
              <option value="2" <?php if ($this->formvars['month_m']=='2') { ?> selected<?php } ?>>Februar</option>
              <option value="3" <?php if ($this->formvars['month_m']=='3') { ?> selected<?php } ?>>März</option>
              <option value="4" <?php if ($this->formvars['month_m']=='4') { ?> selected<?php } ?>>April</option>
              <option value="5" <?php if ($this->formvars['month_m']=='5') { ?> selected<?php } ?>>Mai</option>
              <option value="6" <?php if ($this->formvars['month_m']=='6') { ?> selected<?php } ?>>Juni</option>
              <option value="7" <?php if ($this->formvars['month_m']=='7') { ?> selected<?php } ?>>Juli</option>
              <option value="8" <?php if ($this->formvars['month_m']=='8') { ?> selected<?php } ?>>August</option>
              <option value="9"<?php if ($this->formvars['month_m']=='9') { ?> selected<?php } ?>>September</option>
              <option value="10" <?php if ($this->formvars['month_m']=='10') { ?> selected<?php } ?>>Oktober</option>
              <option value="11" <?php if ($this->formvars['month_m']=='11') { ?> selected<?php } ?>>November</option>
              <option value="12" <?php if ($this->formvars['month_m']=='12') { ?> selected<?php } ?>>Dezember</option>
            </select></td>
          <td align="center"><select name="year_m">
              <option value="">...</option>
              <? 
              for($i = 2005; $i <= date('Y'); $i++){
              	echo '<option value="'.$i.'"';
              	if ($this->formvars['year_m'] == $i){
              		echo ' selected';
              	}
              	echo '>'.$i.'</option>';
              }
              ?>
            </select></td>
        </tr>
      </table> <table align="center" id="tableweek" style="display: <?php if ($this->formvars['zeitraum']=='week'){ echo 'block';} else { echo 'none';} ?>" width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr> 
          <th width="71%">Woche:</th>
          <th width="29%">Jahr:</th>
        </tr>
        <tr> 
          <td align="center"> <select name="week_w">
              <option value="">...bitte wählen</option>
              <?php for ($i=1; $i<=51; $i++) { ?>
              <option value="<?php echo $i;?>" <?php if ($this->formvars['week_w']==$i) { ?> selected<?php } ?> ><?php echo $i.'. KW'; ?></option>
              <?php } ?>
            </select></td>
          <td align="center"><select name="year_w">
              <option value="">...</option>
              <? 
              for($i = 2005; $i <= date('Y'); $i++){
              	echo '<option value="'.$i.'"';
              	if ($this->formvars['year_m'] == $i){
              		echo ' selected';
              	}
              	echo '>'.$i.'</option>';
              }
              ?>
            </select></td>
        </tr>
      </table>
      <table align="center" id="tableday" style="display:<?php if ($this->formvars['zeitraum']=='day'){ echo 'block';} else { echo 'none';} ?>" width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr align="center"> 
          <th width="100%">Tag:</th>
          <th width="100%">Monat:</th>
          <th width="100%">Jahr:</th>
        </tr>
        <tr align="center"> 
          <td align="center"><select name="day_d">
              <option value="">...</option>
              <?php for ($i=1; $i<=31; $i++) { ?>
              <option value="<?php echo $i; ?>" <?php if ($this->formvars['day_d']==$i) { ?> selected<?php } ?>><?php echo $i; ?></option>
              <?php } ?>
            </select></td>
          <td align="center"><select name="month_d">
              <option value="">...bitte wählen</option>
              <option value="1" <?php if ($this->formvars['month_d']=='1') { ?> selected<?php } ?>>Januar</option>
              <option value="2" <?php if ($this->formvars['month_d']=='2') { ?> selected<?php } ?>>Februar</option>
              <option value="3" <?php if ($this->formvars['month_d']=='3') { ?> selected<?php } ?>>März</option>
              <option value="4" <?php if ($this->formvars['month_d']=='4') { ?> selected<?php } ?>>April</option>
              <option value="5" <?php if ($this->formvars['month_d']=='5') { ?> selected<?php } ?>>Mai</option>
              <option value="6" <?php if ($this->formvars['month_d']=='6') { ?> selected<?php } ?>>Juni</option>
              <option value="7" <?php if ($this->formvars['month_d']=='7') { ?> selected<?php } ?>>Juli</option>
              <option value="8" <?php if ($this->formvars['month_d']=='8') { ?> selected<?php } ?>>August</option>
              <option value="9" <?php if ($this->formvars['month_d']=='9') { ?> selected<?php } ?>>September</option>
              <option value="10" <?php if ($this->formvars['month_d']=='10') { ?> selected<?php } ?>>Oktober</option>
              <option value="11" <?php if ($this->formvars['month_d']=='11') { ?> selected<?php } ?>>November</option>
              <option value="12" <?php if ($this->formvars['month_d']=='12') { ?> selected<?php } ?>>Dezember</option>
            </select></td>
          <td align="center"><select name="year_d">
              <option value="">...</option>
              <? 
              for($i = 2005; $i <= date('Y'); $i++){
              	echo '<option value="'.$i.'"';
              	if ($this->formvars['year_m'] == $i){
              		echo ' selected';
              	}
              	echo '>'.$i.'</option>';
              }
              ?>
            </select></td>
        </tr>
      </table>
      <table align="center" id="tableera" style="display:<?php if ($this->formvars['zeitraum']=='era'){ echo 'block';} else { echo 'none';} ?>" width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr> 
          <th>&nbsp;</th>
          <th>Tag:</th>
          <th>Monat:</th>
          <th><strong>Jahr:</strong></th>
        </tr>
        <tr> 
          <td>von: </td>
          <td><select name="day_e1">
              <option value="">...</option>
              <?php for ($i=1; $i<=31; $i++) { ?>
              <option value="<?php echo $i; ?>" <?php if ($this->formvars['day_e1']==$i) { ?> selected<?php } ?>><?php echo $i; ?></option>
              <?php } ?>
            </select></td>
          <td><select name="month_e1">
              <option value="">...bitte wählen</option>
              <option value="1" <?php if ($this->formvars['month_e1']=='1') { ?> selected<?php } ?>>Januar</option>
              <option value="2" <?php if ($this->formvars['month_e1']=='2') { ?> selected<?php } ?>>Februar</option>
              <option value="3" <?php if ($this->formvars['month_e1']=='3') { ?> selected<?php } ?>>März</option>
              <option value="4" <?php if ($this->formvars['month_e1']=='4') { ?> selected<?php } ?>>April</option>
              <option value="5" <?php if ($this->formvars['month_e1']=='5') { ?> selected<?php } ?>>Mai</option>
              <option value="6" <?php if ($this->formvars['month_e1']=='6') { ?> selected<?php } ?>>Juni</option>
              <option value="7" <?php if ($this->formvars['month_e1']=='7') { ?> selected<?php } ?>>Juli</option>
              <option value="8" <?php if ($this->formvars['month_e1']=='8') { ?> selected<?php } ?>>August</option>
              <option value="9" <?php if ($this->formvars['month_e1']=='9') { ?> selected<?php } ?>>September</option>
              <option value="10" <?php if ($this->formvars['month_e1']=='10') { ?> selected<?php } ?>>Oktober</option>
              <option value="11" <?php if ($this->formvars['month_e1']=='11') { ?> selected<?php } ?>>November</option>
              <option value="12" <?php if ($this->formvars['month_e1']=='12') { ?> selected<?php } ?>>Dezember</option>
            </select></td>
          <td><select name="year_e1">
              <option value="">...</option>
              <? 
              for($i = 2005; $i <= date('Y'); $i++){
              	echo '<option value="'.$i.'"';
              	if ($this->formvars['year_m'] == $i){
              		echo ' selected';
              	}
              	echo '>'.$i.'</option>';
              }
              ?>
            </select></td>
        </tr>
        <tr> 
          <th>&nbsp;</th>
          <th>Tag:</th>
          <th>Monat:</th>
          <th><strong>Jahr:</strong></th>
        </tr>
        <tr> 
          <td>bis: </td>
          <td><select name="day_e2">
              <option value="">...</option>
              <?php for ($i=1; $i<=31; $i++) { ?>
              <option value="<?php echo $i; ?>" <?php if ($this->formvars['day_e2']==$i) { ?> selected<?php } ?>><?php echo $i; ?></option>
              <?php } ?>
            </select></td>
          <td><select name="month_e2">
              <option value="">...bitte wählen</option>
              <option value="1" <?php if ($this->formvars['month_e2']=='1') { ?> selected<?php } ?>>Januar</option>
              <option value="2" <?php if ($this->formvars['month_e2']=='2') { ?> selected<?php } ?>>Februar</option>
              <option value="3" <?php if ($this->formvars['month_e2']=='3') { ?> selected<?php } ?>>März</option>
              <option value="4" <?php if ($this->formvars['month_e2']=='4') { ?> selected<?php } ?>>April</option>
              <option value="5" <?php if ($this->formvars['month_e2']=='5') { ?> selected<?php } ?>>Mai</option>
              <option value="6" <?php if ($this->formvars['month_e2']=='6') { ?> selected<?php } ?>>Juni</option>
              <option value="7" <?php if ($this->formvars['month_e2']=='7') { ?> selected<?php } ?>>Juli</option>
              <option value="8" <?php if ($this->formvars['month_e2']=='8') { ?> selected<?php } ?>>August</option>
              <option value="9" <?php if ($this->formvars['month_e2']=='9') { ?> selected<?php } ?>>September</option>
              <option value="10" <?php if ($this->formvars['month_e2']=='10') { ?> selected<?php } ?>>Oktober</option>
              <option value="11" <?php if ($this->formvars['month_e2']=='11') { ?> selected<?php } ?>>November</option>
              <option value="12" <?php if ($this->formvars['month_e2']=='12') { ?> selected<?php } ?>>Dezember</option>
            </select></td>
          <td><select name="year_e2">
              <option value="">...</option>
              <? 
              for($i = 2005; $i <= date('Y'); $i++){
              	echo '<option value="'.$i.'"';
              	if ($this->formvars['year_m'] == $i){
              		echo ' selected';
              	}
              	echo '>'.$i.'</option>';
              }
              ?>
            </select></td>
        </tr>
      </table>
      
    </td>
  </tr>
  <tr> 
    <td align="right"><hr align="center" color="#000000" size="1"> </td>
  </tr>
  <tr> 
    <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr> 
          <td>&nbsp; </td>
          <td>&nbsp;</td>
          <td align="right" ><img src="<?php echo GRAPHICSPATH;?>ikon_i.gif" onMouseOver="stm(Text[1],Style[0])" onmouseout="htm()"></td>
        </tr>
        <tr> 
          <td align="center" >&nbsp; </td>
          <th colspan="2">Stelle: </th>
        </tr>
        <tr> 
          <td align="center">&nbsp; </td>
          <td colspan="2" align="center"><select name="stelle">
              <!-- onChange="document.GUI.submit()"-->
              <option value=""></option>
              <?php 
			for ($i=0;$i<count($this->stellendaten['ID']);$i++) { ?>
              <option value="<?php echo $this->StellenID=$this->stellendaten['ID'][$i]; ?>" <?php if ($this->formvars['stelle']==$this->stellendaten['ID'][$i]) { ?> selected<?php } ?>><?php echo $this->stellendaten['Bezeichnung'][$i]; ?></option>
              <?php  
           }  ?>
            </select></td>
        <tr> 
          <td align="center">&nbsp;</td>
          <td colspan="2" align="center">&nbsp;</td>
        <tr> 
          <td align="center">&nbsp;</td>
          <th colspan="2" align="center"><strong>Nutzer:</strong></th>
        <tr> 
          <td align="center">&nbsp; </td>
          <td colspan="2" align="center"> <select name="nutzer">
              <!-- onChange="document.GUI.submit()"-->
              <option value=""></option>
              <?php 
			for ($i=0;$i<count($this->UserDaten);$i++) { ?>
              <option value="<?php echo $this->UserID=$this->UserDaten[$i]['ID']; ?>" <?php if ($this->formvars['nutzer']==$this->UserDaten[$i]['ID']) { ?> selected<?php } ?>><?php echo $this->UserDaten[$i]['Name'].', '.$this->UserDaten[$i]['Vorname']; ?></option>
              <?php  
           }  ?>
            </select></td>
      </table></td>
  </tr>
  <tr> 
    <td ><hr align="center" color="#000000" size="1"></td>
  </tr>
  <tr> 
    <td align="center" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td align="center">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td align="right" > <img src="<?php echo GRAPHICSPATH;?>ikon_i.gif" onMouseOver="stm(Text[2],Style[0])" onmouseout="htm()"></td>
        </tr>
        <tr> 
          <td align="center">Säulendiagramm</td>
          <td align="center" colspan="2">Kreisdiagramm</td>
        </tr>
        <tr> 
          <td align="center" ><input type="radio" name="chart" value="bar" <?php if ($this->formvars['chart']=='' OR $this->formvars['chart']=='bar') { ?> checked<?php } ?>></td>
          <td align="center" colspan="2"><input type="radio" name="chart" value="pie" <?php if ( $this->formvars['chart']=='pie') { ?> checked<?php } ?>></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" ><hr align="center" color="#000000" size="1"></td>
  <tr> 
    <td align="center" ><input type="submit" name="go_plus" value="anzeigen"></td>
  </tr>
</table>
<br>
</div>
<!--
<br><br>
<table align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td> <?php include ('kalender.php'); ?>
	</td>
  </tr>
</table>
-->

<input type="hidden" name="go" value="StatistikAuswahl">
	  