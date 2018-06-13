<table width="0%" border="0" cellpadding="5" cellspacing="0">
	<tr><td></td></tr>
  <tr> 
    <td bgcolor="<?php echo BG_FORM ?>"><div align="center"><h2><?php echo $this->titel; ?></h2> 
      </div></td>
  </tr>
  <tr> 
    <td bgcolor="<?php echo BG_FORM ?>"><div align="center">
	
	<?php if ($this->Fehlermeldung!='') { include(LAYOUTPATH."snippets/Fehlermeldung.php"); } ?>
	<span class="fett"><font color="#FF0000">
	<?php if ($this->Meldung!='') { echo $this->Meldung; } ?>
	</font> </span>
	 </div>
	 
    </td>
  </tr>
  <tr> 
    <?php $this->formvars['order']='';?>
    <td bgcolor="<?php echo BG_FORM ?>"><table border="0" cellspacing="0" cellpadding="5">
        <tr bgcolor="#FFFFFF"> 
          <td>&nbsp;</td>
          <td><div align="center"><a href="index.php?go=Antraege_Anzeigen&order=antr_nr&richtung=<?php  if ($this->antrag->richtung=='ASC' OR '') { echo $this->formvars['richtung']='ASC';} else { echo $this->formvars['richtung']='DESC';} ?>" title="nach Antragsnummer sortieren"><span class="fett">Antragsnummer</span></a></div></td>
          <td><div align="center"><a href="index.php?go=Antraege_Anzeigen&order=vermstelle&richtung=<?php	if ($this->antrag->richtung=='ASC' OR '') { echo $this->formvars['richtung']='ASC';} else { echo $this->formvars['richtung']='DESC';} ?>" title="nach Vermessungstelle sortieren"><span class="fett">Vermessungsstelle</span></a></div></td>
          <td ><div align="center"><a href="index.php?go=Antraege_Anzeigen&order=verm_art&richtung=<?php	if ($this->antrag->richtung=='ASC' OR '') { echo $this->formvars['richtung']='ASC';} else { echo $this->formvars['richtung']='DESC';} ?>" title="nach Vermessungsart sortieren"><span class="fett">Vermessungsart</span></a></div></td>
          <td>&nbsp;&nbsp;<a href="index.php?go=Antraege_Anzeigen&order=datum&richtung=<?php  if ($this->antrag->richtung=='ASC' OR '') { echo $this->formvars['richtung']='ASC';} else { echo $this->formvars['richtung']='DESC';} ?>" title="nach Datum sortieren"><span class="fett">Datum</span></a>&nbsp;&nbsp;&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="2">&nbsp;</td>
        </tr>
        <?php
		 for ($i=0;$i<(count ($this->antrag->antragsliste));$i++) {
        ?>
        <tr align="left" bgcolor="<?php if ($i%2!=0) {
							 echo '#FFFFFF';
						 		}
		                   else {
							 echo '#EBEBEB';}?>"> 
          <td><div align="left">
            <input type="radio" name="antr_selected" value="<? echo $this->antrag->antragsliste[$i]['antr_nr'].'~'.$this->antrag->antragsliste[$i]['stelle_id']; ?>"<?php
			 if ($this->antrag->antragsliste[$i]['antr_nr'].'~'.$this->antrag->antragsliste[$i]['stelle_id']==$this->formvars['antr_selected']) { ?> checked<?php } ?>>
          </div></td>
          <td><?php echo $this->antrag->antragsliste[$i]['antr_nr']; ?>
          <div align="left"></div></td>
          <td><?php echo $this->antrag->antragsliste[$i]['vermst']; ?>
          <div align="left"></div></td>
          <td><?php echo $this->antrag->antragsliste[$i]['vermart']?> 
            <div align="left"></div></td>
          <td><div align="left"><?php echo $this->formvars['datum']=$this->antrag->antragsliste[$i]['datum']; ?></div></td>
          <td><div align="left"><a href="index.php?go=Nachweis_antragsnr_form_aufrufen&antr_nr=<? echo $this->antrag->antragsliste[$i]['antr_nr'];?>&stelle_id=<? echo $this->antrag->antragsliste[$i]['stelle_id'];?>" title="bearbeiten"><img src="graphics/button_edit.png" border="0"></a></div></td>
          <td><div align="left"><a href="index.php?go=Antrag_loeschen&antr_nr=<?php echo $this->antrag->antragsliste[$i]['antr_nr']; ?>&stelle_id=<? echo $this->antrag->antragsliste[$i]['stelle_id'];?>" title="löschen"><img src="graphics/button_drop.png" border="0"></a></div></td>
        </tr>
        <?php
		}
		?>
      </table>
    </td>
  </tr>
  <tr> 
    <td bgcolor="<?php echo BG_FORM ?>">
    	<select name="go_plus" onChange="document.GUI.submit();"> 
        <option value="">---</option>
       <?php if($this->Stelle->isFunctionAllowed('Antraganzeige_Zugeordnete_Dokumente_Anzeigen')) { ?>
        <option value="Zugeordnete_Dokumente_Anzeigen"<?php if ($this->formvars['antr_verarbeitung']=='erzeugen') { ?> selected<?php } ?>>Zugeordnete Dokumente Anzeigen</option>
       <?php } ?>
				<option value="Festpunkte_in_Karte_Anzeigen">Zugeordnete Festpunkte in Karte Anzeigen</option>
				<option value="Festpunkte_in_Liste_Anzeigen">Zugeordnete Festpunkte in Liste Anzeigen</option>
		 		<option value="Festpunkte_in_KVZ_schreiben">Zugeordnete Festpunkte in KVZ Schreiben</option>
        <option value="Uebergabeprotokoll_Zusammenstellen"<?php if ($this->formvars['antr_verarbeitung']=='erzeugen') { ?> selected<?php } ?>>Übergabeprotokoll erzeugen</option>
        <option value="Zusammenstellen_Zippen">Dokumente zusammenstellen & packen</option>
				<option value="Zusammenstellen_Zippen_mit_Uebersichten">Dokumente mit Übersichten zusammenstellen & packen</option>
      </select>
      <input type="hidden" name="go" value="Antraganzeige">
      <input type="hidden" name="file" value="1">
      <input type="hidden" name="Lfd" value="1">
 			<input type="hidden" name="Riss-Nummer" value="1"> 
 			<input type="hidden" name="Antrags-Nummer" value="1"> 
			<input type="hidden" name="FFR" value="1">
			<input type="hidden" name="KVZ" value="1">
			<input type="hidden" name="GN" value="1">
			<input type="hidden" name="andere" value="1"> 
			<input type="hidden" name="Datum" value="1">
			<input type="hidden" name="Datei" value="1">
 			<input type="hidden" name="gemessendurch" value="1">
			<input type="hidden" name="Gueltigkeit" value="1">
    </td>
  </tr>
</table>
