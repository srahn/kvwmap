<script type="text/javascript">
<!--

function vorlage(){
	document.GUI.go.value='Nachweisformular_Vorlage';
	document.GUI.submit();
}

function add_to_order(order){
	if(document.GUI.order.value != '')document.GUI.order.value = document.GUI.order.value + ',';
	document.GUI.order.value = document.GUI.order.value + order;
	document.GUI.submit();
}

function remove_from_order(order){
	var before = document.GUI.order.value;
	before = before.replace(order+',', '');
	before = before.replace(','+order, '');
	var after = before.replace(order, '');
	document.GUI.order.value = after;
	document.GUI.submit();
}

function set_richtung(richtung){
	document.GUI.richtung.value = richtung;
	document.GUI.submit();
}

//-->
</script>

<? 
	function build_order_links($orderstring, $richtung){
		if($orderstring != ''){
			$orderaliases = array('flurid' => 'Flur', 'stammnr' => 'Antragsnr.', 'rissnummer' => 'Rissnr.', 'art' => 'Dokumentart', 'blattnummer' => 'Blattnr.', 'datum' => 'Datum', 'fortfuehrung' => 'Fortfuehrung', 'vermst' => 'Vermstelle', 'gueltigkeit' => 'Gueltigkeit', 'format' => 'Format');
			$orders = explode(',', $orderstring);
			foreach($orders as $order){
				$orderlinks[] = '<a href="javascript:remove_from_order(\''.$order.'\');" title="'.$orderaliases[$order].' aus Sortierung entfernen">'.$orderaliases[$order].'</a>';
			}
			if($richtung == 'DESC')$richtungslink = '&nbsp;<a href="javascript:set_richtung(\'ASC\');" title="absteigend"><img src="'.GRAPHICSPATH.'pfeil.gif"></a>';
			else $richtungslink = '&nbsp;<a href="javascript:set_richtung(\'DESC\');" title="aufsteigend"><img src="'.GRAPHICSPATH.'pfeil2.gif"></a>';
			return implode(', ', $orderlinks).$richtungslink;
		}
	}

?>

<input type="hidden" name="go" value="Nachweisanzeige">
<input type="hidden" name="order" value="<? echo $this->formvars['order']; ?>">
<input type="hidden" name="richtung" value="<? echo $this->formvars['richtung']; ?>">
	
<table width="0%" border="0" cellpadding="5" cellspacing="0">
  <tr> 
    <td bgcolor="<?php echo BG_FORM ?>"><table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr> 
          <td><div align="center"><h2><?php echo $this->titel; ?></h2></div></td>
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
          <td>Gesucht nach:<span class="fett"> 
            <?php
						if($this->formvars['gueltigkeit'] == 1){ echo ' nur gültige '; }
						if($this->formvars['gueltigkeit'] == '0'){ echo ' nur ungültige '; }
						if($this->formvars['gueltigkeit'] == ''){ echo ' alle '; }
						if ($this->formvars['suchffr']){ echo ' FFR, '; }
						if ($this->formvars['suchkvz']){ echo ' KVZ, '; }
						if ($this->formvars['suchgn']){ echo ' GN, '; }
						if ($this->formvars['suchan']){ echo ' andere'; }
            switch ($this->formvars['abfrageart']) {
              case 'indiv_nr' : { 
								if($this->formvars['suchgemarkung'] != '') echo ' in Gemarkung: '.$this->formvars['suchgemarkung'];
								if($this->formvars['suchflur'] != '') echo ' in Flur: '.str_pad($this->formvars['suchflur'],3,'0',STR_PAD_LEFT);
                if($this->formvars['suchstammnr'] != '')echo ' mit Antragsnummer: '.str_pad(intval($this->formvars['suchstammnr']),ANTRAGSNUMMERMAXLENGTH,'0',STR_PAD_LEFT);
                if($this->formvars['suchrissnr'] != '')echo ' mit Rissnummer: '.$this->formvars['suchrissnr'];
                if($this->formvars['suchfortf'] != '')echo ' mit Fortführung: '.$this->formvars['suchfortf'];
								if($this->formvars['datum'] != '')echo ' von '.$this->formvars['datum'];
								if($this->formvars['datum2'] != '')echo ' bis '.$this->formvars['datum2'];
								if($this->formvars['VermStelle'] != '')echo ' von Vermessungsstelle '.$this->formvars['VermStelle'];
              } break;
              case 'antr_nr' : { 
                ?> aus Vorbereitungsnummer - <?php echo $this->formvars['suchantrnr'];
              } break;
              case 'poly' : {
                ?> in Suchpolygon<?php 
              } break;
            }
              ?></span>                </td>
        </tr>
		<tr> 
			<td>Sortiert nach:
				<span class="fett"><? echo build_order_links($this->formvars['order'], $this->formvars['richtung']); ?></span>
			</td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td bgcolor="<?php echo BG_FORM ?>"><?php
	 if ($this->nachweis->erg_dokumente > 0) { ?>
	 <table border="0" cellspacing="0" cellpadding="5">
        <tr style="outline: 1px solid grey;" bgcolor="#FFFFFF"> 
          <td height="40" width="56"><div align="center"><span class="fett">Auswahl</span></div></td>
          <td width="18"><span class="fett">ID</span></td>
			<? if(strpos($this->formvars['order'], 'flurid') === false){ ?>
				<td align="center" width="65"><a href="javascript:add_to_order('flurid');" title="nach individueller Nummer sortieren"><span class="fett">Flur</span></a></td>
			<? }else{echo '<td align="center" width="65"><span class="fett">Flur</span></td>';} ?>
          <? if(NACHWEIS_PRIMARY_ATTRIBUTE != 'rissnummer'){			
				if(strpos($this->formvars['order'], 'stammnr') === false){ ?>
					<td align="center" width="64"><a href="javascript:add_to_order('stammnr');" title="nach Antragsnr. sortieren"><span class="fett">Antragsnr.</span></a></td>
				<? }else{echo '<td align="center" width="64"><span class="fett">Antragsnr.</span></td>';}
			} ?>
			<? if(strpos($this->formvars['order'], 'rissnummer') === false){ echo strpos($this->formvars['order'], 'rissnummer');?>
				<td align="center" width="64"><a href="javascript:add_to_order('rissnummer');" title="nach Rissnr. sortieren"><span class="fett">Rissnr.</span></a></td>
			<? }else{echo '<td align="center" width="64"><span class="fett">Rissnr.</span></td>';} ?>
          <? if(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer'){
				if(strpos($this->formvars['order'], 'stammnr') === false){ ?>
					<td align="center" width="64"><a href="javascript:add_to_order('stammnr');" title="nach Antragsnr. sortieren"><span class="fett">Antragsnr.</span></a></td>
				<? }else{echo '<td align="center" width="64"><span class="fett">Antragsnr.</span></td>';}
			} ?>            
			<? if(strpos($this->formvars['order'], 'art') === false){ ?>
				<td align="center" width="137"><a href="javascript:add_to_order('art');" title="nach Dokumentenart sortieren"><span class="fett">Dokumentart</span></a></td>
			<? }else{echo '<td align="center" width="137"><span class="fett">Dokumentart</span></td>';} ?>
			<? if(strpos($this->formvars['order'], 'blattnummer') === false){ ?>
				<td align="center" width="87"><a href="javascript:add_to_order('blattnummer');" title="nach Blattnummer sortieren"><span class="fett">Blattnr.</span></a></td>
			<? }else{echo '<td align="center" width="137"><span class="fett">Blattnr.</span></td>';} ?>
			<? if(strpos($this->formvars['order'], 'datum') === false){ ?>
				<td align="center" width="45"><a href="javascript:add_to_order('datum');" title="nach Datum sortieren"><span class="fett">Datum</span></a></td>
			<? }else{echo '<td align="center" width="137"><span class="fett">Datum</span></td>';} ?>
			<? if(strpos($this->formvars['order'], 'fortfuehrung') === false){ ?>
				<td align="center" width="45"><a href="javascript:add_to_order('fortfuehrung');" title="nach Fortführung sortieren"><span class="fett">Fortführung</span></a></td>
			<? }else{echo '<td align="center" width="137"><span class="fett">Fortführung</span></td>';} ?>
			<? if(strpos($this->formvars['order'], 'vermst') === false){ ?>
				<td align="center" width="137"><a href="javascript:add_to_order('vermst');" title="nach Vermessungsstelle sortieren"><span class="fett">VermStelle</span></a></td>
			<? }else{echo '<td align="center" width="137"><span class="fett">VermStelle</span></td>';} ?>
			<? if(strpos($this->formvars['order'], 'gueltigkeit') === false){ ?>
				<td align="center" width="137"><a href="javascript:add_to_order('gueltigkeit');" title="nach Gültigkeit sortieren"><span class="fett">Gültigkeit</span></a></td>
			<? }else{echo '<td align="center" width="137"><span class="fett">Gültigkeit</span></td>';} ?>
			<? if(strpos($this->formvars['order'], 'format') === false){ ?>
				<td align="center" width="137"><a href="javascript:add_to_order('format');" title="nach Blattformat sortieren"><span class="fett">Format</span></a></td>
			<? }else{echo '<td align="center" width="137"><span class="fett">Format</span></td>';} ?>	
          <td colspan="3"><div align="center"><?php    echo $this->nachweis->erg_dokumente.' Treffer';   ?></div></td>
        </tr>
        <?php
		$bgcolor = '#FFFFFF';
     for ($i=0;$i<$this->nachweis->erg_dokumente;$i++) {
        ?>
        <tr style="outline: 1px dotted grey;" onmouseover="document.getElementById('vorschau').innerHTML='';" bgcolor="
			<? $orderelem = explode(',', $this->formvars['order']);
			if ($this->nachweis->Dokumente[$i][$orderelem[0]] != $this->nachweis->Dokumente[$i-1][$orderelem[0]]){
				if($bgcolor == '#EBEBEB'){
					echo '#FFFFFF';
					$bgcolor = '#FFFFFF';
				}
				else{
					echo '#EBEBEB';
					$bgcolor = '#EBEBEB';
				}
			}else echo $bgcolor;
            ?>
			"> 
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
          <? if(NACHWEIS_PRIMARY_ATTRIBUTE != 'rissnummer'){ ?>  
          <td><div align="center"><?php echo $this->formvars['stammnr']=$this->nachweis->Dokumente[$i]['stammnr']; ?></div></td>
          <? } ?>
          <td><div align="center"><?php echo $this->formvars['rissnummer']=$this->nachweis->Dokumente[$i]['rissnummer']; ?></div></td>
          <? if(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer'){ ?>
          <td><div align="center"><?php echo $this->formvars['stammnr']=$this->nachweis->Dokumente[$i]['stammnr']; ?></div></td>
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
          <td><div align="center"><?php echo $this->nachweis->Dokumente[$i]['datum']; ?></div></td>
          <td><div align="center"><?php echo $this->formvars['fortf']=$this->nachweis->Dokumente[$i]['fortfuehrung']; ?></div></td>
          <td><div align="center"><?php echo $this->formvars['vermstelle']=$this->nachweis->Dokumente[$i]['vermst']; ?></div></td>
          <td><div align="center"><?php echo $this->formvars['gueltigkeit']=$this->nachweis->Dokumente[$i]['gueltigkeit']; ?></div></td>
          <td><div align="center"><?php echo $this->formvars['format']=$this->nachweis->Dokumente[$i]['format']; ?> 
            </div></td>
          <td width="16"><a target="_blank" onmouseover="ahah('<? echo URL.APPLVERSION.'index.php'; ?>', 'go=document_vorschau&id=<?php echo $this->nachweis->Dokumente[$i]['id']; ?>', new Array(document.getElementById('vorschau')), new Array('sethtml'));" href="index.php?go=document_anzeigen&ohnesession=1&id=<?php echo $this->nachweis->Dokumente[$i]['id']; ?>&file=1" title="Ansicht"><img src="graphics/button_ansicht.png" border="0"></a></td>
          <td width="15">
          	<? if($this->Stelle->isFunctionAllowed('Nachweise_bearbeiten')){ ?>
          	<a href="index.php?go=Nachweisformular&id=<?php echo $this->nachweis->Dokumente[$i]['id'];?>&order=<? echo $this->formvars['order'] ?>" title="bearbeiten"><img src="graphics/button_edit.png" border="0"></a></td>
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
          		<a href="javascript:vorlage();"><span class="fett">--> markierte als Vorlage übernehmen</span></a></td>
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
          <td><span class="fett">
            <?php $this->FormObjAntr_nr->outputHTML();
    					echo $this->FormObjAntr_nr->html;?>
          </span></td>
          <td> <select name="go_plus" onChange="document.GUI.submit()">
              <option value="">---</option>
              <option value="zum_Auftrag_hinzufuegen"<?php if ($this->formvars['nachweisaction']=='markierte_zum_Auftrag_hinzufuegen') { ?> selected<?php } ?>>zu 
              Auftrag hinzufügen</option>
              <option value="aus_Auftrag_entfernen"<?php if ($this->formvars['nachweisaction']=='markierte_aus_Auftrag_entfernen') { ?> selected<?php } ?>>aus 
              Auftrag entfernen</option>
            </select><span class="fett">&nbsp;</span>          </td>
        </tr>
      </table>
	  <?php 
	  } else {
	  ?>
	  <span class="fett">Es konnten keine Dokumente zu der Auswahl gefunden werden.<br>
Wählen Sie neue Suchparameter.</span><br>
	  <?php } ?>
         <a href="index.php?go=Nachweisrechercheformular&datum=<? echo $this->formvars['datum']; ?>&datum2=<? echo $this->formvars['datum2']; ?>&VermStelle=<? echo $this->formvars['VermStelle']; ?>">&lt;&lt; zur&uuml;ck
         zur Suche</a></td>
  </tr>
  <tr> 
    <td bgcolor="<?php echo BG_FORM ?>"> 
    </td>
  </tr>
</table>


<!--[IF !IE]> -->
<div id="vorschau" style="position: fixed; left:50%; margin-left:-150px;  top:190px; "></div>
<!-- <![ENDIF]-->
 <!--[IF IE]>
<div id="vorschau" style="position: absolute; left:50%; margin-left:-150px; top: expression((190 + (ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop)) + 'px');"></div>
<![ENDIF]-->
