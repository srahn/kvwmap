<script type="text/javascript">
<!--

function vorlage(){
	document.GUI.go.value='Nachweisformular_Vorlage';
	document.GUI.submit();
}

//-->
</script>

<table width="0%" border="0" cellpadding="5" cellspacing="0">
  <tr> 
    <td bgcolor="<?php echo BG_FORM ?>"><table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr> 
          <td><div align="center"><strong><font size="+1"><?php echo $this->titel; ?></font></strong></div></td>
        </tr>
        <tr> 
          <td><hr><?php
		    if ($this->Fehlermeldung!='') {

include(LAYOUTPATH."snippets/Fehlermeldung.php");

}

		  ?>
	  </td>
        </tr>
        <tr> 
          <td>Gesucht nach:<br><b> 
            <?php
            switch ($this->formvars['abfrageart']) {
              case 'indiv_nr' : {
                ?>
            Individuelle Nummer in Gemarkung/Flur: <?php echo $this->formvars['suchgemarkungflurid']; ?>
                mit Antragsnummer: <?php echo str_pad(intval($this->formvars['suchstammnr']),STAMMNUMMERMAXLENGTH,'0',STR_PAD_LEFT); ?>
                mit Rissnummer: <?php echo $this->formvars['suchrissnr']; ?>
                mit Fortführung: <?php echo $this->formvars['suchfortf']; ?>
                von <?php
                if ($this->formvars['suchffr']){ echo ' FFR, '; }
                if ($this->formvars['suchkvz']){ echo ' KVZ, '; }
                if ($this->formvars['suchgn']){ echo ' GN'; }
              } break;
              case 'antr_nr' : { 
                ?>Vorbereitungsnummer - <?php echo $this->formvars['suchantrnr'];
                if ($this->formvars['suchffr']){ echo ' FFR, '; }
                if ($this->formvars['suchkvz']){ echo ' KVZ, '; }
                if ($this->formvars['suchgn']){ echo ' GN'; }
              } break;
              case 'poly' : {
                ?>Suchpolygon<?php 
              } break;
            }
              ?></b>                </td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td bgcolor="<?php echo BG_FORM ?>"><?php
	 if ($this->nachweis->erg_dokumente>0) { 
 	 $this->formvars['order']='';?>
	 <table border="0" cellspacing="0" cellpadding="5">
        <tr bgcolor="#FFFFFF"> 
          <td width="56"><div align="center"><strong>Auswahl</strong></div></td>
          <td width="18"><strong>ID</strong></td>
          <td width="65"><div align="center"><a href="index.php?go=Nachweisanzeige&order=FlurID&richtung=<?php
            if ($this->nachweis->richtung=='ASC' OR '') { echo $this->formvars['richtung']='ASC';} else { echo $this->formvars['richtung']='DESC';} ?>" title="nach individueller Nummer sortieren"><strong>Flur-ID</strong></a></div></td>
          <? if(NACHWEIS_PRIMARY_ATTRIBUTE != 'Rissnummer'){ ?>  
          <td width="64"><div align="center"><a href="index.php?go=Nachweisanzeige&order=stammnr&richtung=<?php
            if ($this->nachweis->richtung=='ASC' OR '') { echo $this->formvars['richtung']='ASC';} else { echo $this->formvars['richtung']='DESC';} ?>" title="nach Antragsnr. sortieren"><strong>Antragsnr.</strong></a></div></td>
          <? } ?>
          <td width="64"><div align="center"><a href="index.php?go=Nachweisanzeige&order=rissnummer&richtung=<?php
            if ($this->nachweis->richtung=='ASC' OR '') { echo $this->formvars['richtung']='ASC';} else { echo $this->formvars['richtung']='DESC';} ?>" title="nach Rissnr. sortieren"><strong>Rissnr.</strong></a></div></td>
          <? if(NACHWEIS_PRIMARY_ATTRIBUTE == 'Rissnummer'){ ?>
          <td width="64"><div align="center"><a href="index.php?go=Nachweisanzeige&order=stammnr&richtung=<?php
            if ($this->nachweis->richtung=='ASC' OR '') { echo $this->formvars['richtung']='ASC';} else { echo $this->formvars['richtung']='DESC';} ?>" title="nach Antragsnr. sortieren"><strong>Antragsnr.</strong></a></div></td>
          <? } ?>            
          <td width="137"><div align="center"><a href="index.php?go=Nachweisanzeige&order=art&richtung=<?php
            if ($this->nachweis->richtung=='ASC' OR '') { echo $this->formvars['richtung']='ASC';} else { echo $this->formvars['richtung']='DESC';} ?>" title="nach Dokumentenart sortieren"><strong>Art 
              d. Documents</strong></a></div></td>
          <td width="87"><div align="center"><a href="index.php?go=Nachweisanzeige&order=blattnummer&richtung=<?php
            if ($this->nachweis->richtung=='ASC' OR '') { echo $this->formvars['richtung']='ASC';} else { echo $this->formvars['richtung']='DESC';} ?>" title="nach Blattnummer sortieren"><strong>Blattnummer</strong></a></div></td>
          <td width="45">&nbsp;&nbsp;<a href="index.php?go=Nachweisanzeige&order=datum&richtung=<?php
            if ($this->nachweis->richtung=='ASC' OR '') { echo $this->formvars['richtung']='ASC';} else { echo $this->formvars['richtung']='DESC';} ?>" title="nach Datum sortieren"><strong>Datum</strong></a>&nbsp;&nbsp;&nbsp;</td>
          <td width="45">&nbsp;&nbsp;<a href="index.php?go=Nachweisanzeige&order=fortfuehrung&richtung=<?php
            if ($this->nachweis->richtung=='ASC' OR '') { echo $this->formvars['richtung']='ASC';} else { echo $this->formvars['richtung']='DESC';} ?>" title="nach Fortführung sortieren"><strong>Fortführung</strong></a>&nbsp;&nbsp;&nbsp;</td>
          <td width="75"><div align="center"><a href="index.php?go=Nachweisanzeige&order=vermstelle&richtung=<?php
            if ($this->nachweis->richtung=='ASC' OR '') { echo $this->formvars['richtung']='ASC';} else { echo $this->formvars['richtung']='DESC';} ?>" title="nach Vermessungsstelle sortieren"><strong>VermStelle</strong></a></div></td>
          <td width="75"><div align="center"><a href="index.php?go=Nachweisanzeige&order=gueltigkeit&richtung=<?php
            if ($this->nachweis->richtung=='ASC' OR '') { echo $this->formvars['richtung']='ASC';} else { echo $this->formvars['richtung']='DESC';} ?>" title="nach Gültigkeit sortieren"><strong>Gültigkeit</strong></a></div></td>
          <td width="48"><div align="center"><a href="index.php?go=Nachweisanzeige&order=format&richtung=<?php
            if ($this->nachweis->richtung=='ASC' OR '') { echo $this->formvars['richtung']='ASC';} else { echo $this->formvars['richtung']='DESC';} ?>" title="nach Blattformat sortieren"><strong>Format</strong></a></div></td>
          <td colspan="3"><div align="center"><?php    echo $this->nachweis->erg_dokumente.' Treffer';   ?></div></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td colspan="15"><hr></td>
        </tr>
        <?php
     for ($i=0;$i<$this->nachweis->erg_dokumente;$i++) {
        ?>
        <tr bgcolor="<?php if ($i%2!=0) {
               echo '#FFFFFF';
                }
                       else {
               echo '#EBEBEB';}?>"> 
          <td><div align="center"> 
              <input type="checkbox" name="id[<?php echo $this->nachweis->Dokumente[$i]['id']; ?>]" value="<?php echo $this->nachweis->Dokumente[$i]['id']; ?>"<?php 
        # Püfen ob das Dokument markiert werden soll
                
        if ($this->art_markieren=='individuell') {
          if ($this->formvars['id'][$this->nachweis->Dokumente[$i]['id']]!=0) {
            ?> checked<?php
          }
        }
        else {

        if ($this->nachweis->Dokumente[$i]['art'] == ($this->nachweis->Dokumente[$i]['art'] & $this->formvars['art_markieren'])) {
            ?> checked<?php 
          }
        }
        ?>>
            </div></td>
          <td><?php echo $this->formvars['id']=$this->nachweis->Dokumente[$i]['id']; ?></td>
          <td><div align="center"><?php echo $this->formvars['flurid']=$this->nachweis->Dokumente[$i]['flurid']; ?></div></td>
          <? if(NACHWEIS_PRIMARY_ATTRIBUTE != 'Rissnummer'){ ?>  
          <td><div align="center"><?php echo $this->formvars['stammnr']=str_pad($this->nachweis->Dokumente[$i]['stammnr'],STAMMNUMMERMAXLENGTH,'0',STR_PAD_LEFT); ?></div></td>
          <? } ?>
          <td><div align="center"><?php echo $this->formvars['rissnr']=str_pad($this->nachweis->Dokumente[$i]['rissnummer'],STAMMNUMMERMAXLENGTH,'0',STR_PAD_LEFT); ?></div></td>
          <? if(NACHWEIS_PRIMARY_ATTRIBUTE == 'Rissnummer'){ ?>
          <td><div align="center"><?php echo $this->formvars['stammnr']=str_pad($this->nachweis->Dokumente[$i]['stammnr'],STAMMNUMMERMAXLENGTH,'0',STR_PAD_LEFT); ?></div></td>
          <? } ?>
          <td><div align="center"> 
              <?php if ($this->formvars['art']=$this->nachweis->Dokumente[$i]['art']=='100'){?>
              FFR 
              <?php               
                    }
                    elseif($this->formvars['art']=$this->nachweis->Dokumente[$i]['art']=='010'){?>
              KVZ 
              <?php               
                    }
                    elseif($this->formvars['art']=$this->nachweis->Dokumente[$i]['art']=='001'){?>
              GN 
              <?php
                    }
                    elseif($this->formvars['art']=$this->nachweis->Dokumente[$i]['art']=='111'){
                    	echo $this->nachweis->Dokumente[$i]['andere_art_name'];
                    }
                ?>
            </div></td>
          <td><div align="center"><?php echo $this->formvars['blattnummer']=str_pad($this->nachweis->Dokumente[$i]['blattnummer'],BLATTNUMMERMAXLENGTH,'0',STR_PAD_LEFT); ?></div></td>
          <td><div align="center"><?php echo $this->formvars['datum']=$this->nachweis->Dokumente[$i]['datum']; ?></div></td>
          <td><div align="center"><?php echo $this->formvars['fortf']=$this->nachweis->Dokumente[$i]['fortfuehrung']; ?></div></td>
          <td><div align="center"><?php echo $this->formvars['vermstelle']=$this->nachweis->Dokumente[$i]['vermst']; ?></div></td>
          <td><div align="center"><?php echo $this->formvars['gueltigkeit']=$this->nachweis->Dokumente[$i]['gueltigkeit']; ?></div></td>
          <td><div align="center"><?php echo $this->formvars['format']=$this->nachweis->Dokumente[$i]['format']; ?> 
            </div></td>
          <td width="16"><a target="_blank" href="index.php?go=document_anzeigen&ohnesession=1&id=<?php echo $this->nachweis->Dokumente[$i]['id']; ?>&file=1" title="Ansicht"><img src="graphics/button_ansicht.png" border="0"></a></td>
          <td width="15">
          	<? if($this->Stelle->isFunctionAllowed('Nachweise_bearbeiten')){ ?>
          	<a href="index.php?go=Nachweisformular&id=<?php echo $this->nachweis->Dokumente[$i]['id'];?>" title="bearbeiten"><img src="graphics/button_edit.png" border="0"></a></td>
          	<? } ?>
          <td width="30">
          	<? if($this->Stelle->isFunctionAllowed('Nachweisloeschen')){ ?>
          	<a href="index.php?go=Nachweisloeschen&id=<?php echo $this->nachweis->Dokumente[$i]['id']; ?>"  title="löschen"><img src="graphics/button_drop.png" border="0"></a>
          	<? } ?>
          </td>
        </tr>
        <?php
    }
    ?>
      </table>
      <table width="0%" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td>markieren</td>
          <td colspan="3">
          	<select name="art_markieren" onChange="document.GUI.submit()">
	            <option value="">---</option>
	            <option value="111"<?php if ($this->formvars['art_markieren']=='111') { ?> selected<?php } ?>>alle</option>
	            <option value="000"<?php if ($this->formvars['art_markieren']=='000') { ?> selected<?php } ?>>keine</option>
	            <option value="100"<?php if ($this->formvars['art_markieren']=='100') { ?> selected<?php } ?>>nur
	            FFR</option>
	            <option value="010"<?php if ($this->formvars['art_markieren']=='010') { ?> selected<?php } ?>>nur
	            KVZ</option>
	            <option value="001"<?php if ($this->formvars['art_markieren']=='001') { ?> selected<?php } ?>>nur
	            GN</option>
	            <option value="110"<?php if ($this->formvars['art_markieren']=='110') { ?> selected<?php } ?>>FFR
	            + KVZ</option>
	            <option value="101"<?php if ($this->formvars['art_markieren']=='101') { ?> selected<?php } ?>>FFR
	            + GN</option>
	            <option value="011"<?php if ($this->formvars['art_markieren']=='011') { ?> selected<?php } ?>>KVZ
	            + GN</option>
          	</select>
          </td>
          <td>
          	<? if($this->Stelle->isFunctionAllowed('Nachweise_bearbeiten')){ ?>
          		<a href="javascript:vorlage();"><b>--> markierte als Vorlage übernehmen</b></a></td>
          	<? } ?>
        </tr>
        <tr> 
          <td>einblenden</td>
          <td colspan="3"><select name="art_einblenden" onChange="document.GUI.submit()">
              <option value="">---</option>
              <option value="111"<?php if ($this->formvars['art_einblenden']==='111') { ?> selected<?php } ?>>alle</option>
              <option value="0001"<?php if ($this->formvars['art_einblenden']==='0001') { ?> selected<?php } ?>>nur Andere</option>
              <option value="100"<?php if ($this->formvars['art_einblenden']==='100') { ?> selected<?php } ?>>nur 
              FFR</option>
              <option value="010"<?php if ($this->formvars['art_einblenden']==='010') { ?> selected<?php } ?>>nur 
              KVZ</option>
              <option value="001"<?php if ($this->formvars['art_einblenden']==='001') { ?> selected<?php } ?>>nur 
              GN</option>
              <option value="110"<?php if ($this->formvars['art_einblenden']==='110') { ?> selected<?php } ?>>FFR 
              + KVZ</option>
              <option value="101"<?php if ($this->formvars['art_einblenden']==='101') { ?> selected<?php } ?>>FFR 
              + GN</option>
              <option value="011"<?php if ($this->formvars['art_einblenden']==='011') { ?> selected<?php } ?>>KVZ 
              + GN</option>
            </select></td>
        </tr>
        <tr> 
          <td>Vorbereitungsnummer:</td>
          <td><strong>
            <?php $this->FormObjAntr_nr->outputHTML();
    					echo $this->FormObjAntr_nr->html;?>
          </strong></td>
          <td> <select name="go_plus" onChange="document.GUI.submit()">
              <option value="">---</option>
              <option value="zum_Auftrag_hinzufuegen"<?php if ($this->formvars['nachweisaction']=='markierte_zum_Auftrag_hinzufuegen') { ?> selected<?php } ?>>zu 
              Auftrag hinzufügen</option>
              <option value="aus_Auftrag_entfernen"<?php if ($this->formvars['nachweisaction']=='markierte_aus_Auftrag_entfernen') { ?> selected<?php } ?>>aus 
              Auftrag entfernen</option>
            </select><strong>&nbsp;</strong>          </td>
        </tr>
      </table>
	  <?php 
	  } else {
	  ?>
	  <b>Es konnten keine Dokumente zu der Auswahl gefunden werden.<br>
Wählen Sie neue Suchparameter.</b><br>
	  <?php } ?>
         <a href="index.php?go=Nachweisrechercheformular">&lt;&lt; zur&uuml;ck
         zur Auswahl</a></td>
  </tr>
  <tr> 
    <td bgcolor="<?php echo BG_FORM ?>"><input type="hidden" name="go" value="Nachweisanzeige"> 
    </td>
  </tr>
</table>

