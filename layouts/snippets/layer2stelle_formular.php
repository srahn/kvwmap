<?php
  # 2007-01-26 pkvvm
  include(LAYOUTPATH.'languages/layer2stelle_formular_'.$this->user->rolle->language.'.php');
?><script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><h2><?php echo $strTitle; ?></h2></td>
  </tr>
  <tr>
    <td align="center"><?php   
if ($this->Meldung=='Daten der Stelle erfolgreich eingetragen!' OR $this->Meldung=='') {
  $bgcolor=BG_FORM;
}
else {
  $this->Fehlermeldung=$this->Meldung;
  include('Fehlermeldung.php');
  $bgcolor=BG_FORMFAIL;
}
 ?>      
		<table border="0" cellspacing="0" cellpadding="5" style="border:1px solid #C3C7C3">
			<tr align="center">
		    	<td class="fett" colspan="3" style="border-bottom:1px solid #C3C7C3">
		    		<? echo ''.$this->strLayer.' '.$this->formvars['Name'].' '.$strTask.' '.$this->formvars['stellen_name']; ?>
				</td>
		    </tr>
		  	<tr>
		    	<th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strQueryable; ?></th>
		    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
		      		<select name="queryable">
		      			<option <? if($this->formvars['queryable'] == '0'){echo 'selected ';} ?>value="0"><?php echo $this->strNo; ?></option>
		      			<option <? if($this->formvars['queryable'] == 1){echo 'selected ';} ?>value="1"><?php echo $this->strYes; ?></option>		      			
		      		</select>
		  		</td>
		  	</tr>
		  	<tr>
		    	<th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strMinScale; ?></th>
		    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
		      		<input name="minscale" type="text" value="<?php echo $this->formvars['minscale']; ?>" size="25" maxlength="100">
		  		</td>
		  	</tr>
		  	<tr>
		    	<th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strMaxScale; ?></th>
		    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
		      		<input name="maxscale" type="text" value="<?php echo $this->formvars['maxscale']; ?>" size="25" maxlength="100">
		  		</td>
		  	</tr>
		  	<tr>
		    	<th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strOffSite; ?></th>
		    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
		      		<input name="offsite" type="text" value="<?php echo $this->formvars['offsite']; ?>" size="25" maxlength="100">
		  		</td>
		  	</tr>
		  	<tr>
		    	<th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strTransparency; ?></th>
		    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
		      		<input name="transparency" type="text" value="<?php echo $this->formvars['transparency']; ?>" size="25" maxlength="100">
		  		</td>
		  	</tr>
		  	<tr>
		    	<th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strPostLabelCache; ?></th>
		    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
		      	<select name="postlabelcache">
	      			<option <? if($this->formvars['postlabelcache'] == '0'){echo 'selected ';} ?>value="0"><?php echo $this->strNo; ?></option>
	      			<option <? if($this->formvars['postlabelcache'] == 1){echo 'selected ';} ?>value="1"><?php echo $this->strYes; ?></option>		      			
	      		</select>
		  		</td>
		  	</tr>
		  	<tr>
		  		<th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strFilter; ?></th>
		  		<td>
		  			<textarea name="Filter" cols="33" rows="4"><? echo $this->formvars['Filter'] ?></textarea>
		  		</td>
		  	</tr>
		  	<tr>
		    	<th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strTemplate; ?></th>
		    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
		      		<input name="template" type="text" value="<?php echo $this->formvars['template']; ?>" size="25" maxlength="100">
		  		</td>
		  	</tr>
		  	<tr>
		    	<th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strHeader; ?></th>
		    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
		      		<input name="header" type="text" value="<?php echo $this->formvars['header']; ?>" size="25" maxlength="100">
		  		</td>
		  	</tr>
		  	<tr>
		    	<th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strFooter; ?></th>
		    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
		      		<input name="footer" type="text" value="<?php echo $this->formvars['footer']; ?>" size="25" maxlength="100">
		  		</td>
		  	</tr>
		  	<tr>
		    	<th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strSymbolScale; ?></th>
		    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
		      		<input name="symbolscale" type="text" value="<?php echo $this->formvars['symbolscale']; ?>" size="25" maxlength="100">
		  		</td>
		  	</tr>
		  	<tr>
		    	<th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $stRequires; ?></th>
		    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
		      		<!--input name="requires" type="text" value="<?php echo $this->formvars['requires']; ?>" size="25" maxlength="100"-->
							<select name="requires">
								<option value="">--- Auswahl ---</option>
								<?
									for($i = 0; $i < count($this->grouplayers['ID']); $i++){
										echo '<option value="'.$this->grouplayers['ID'][$i].'" ';
										if($this->formvars['requires'] == $this->grouplayers['ID'][$i])echo 'selected="true"';
										echo ' >'.$this->grouplayers['Bezeichnung'][$i].'</option>';
									}
								?>
							</select>
		  		</td>
		  	</tr>		
		  	<tr>
		    	<th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $stStartAktiv; ?></th>
		    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
		      		<select name="startaktiv">
		      			<option <? if($this->formvars['start_aktiv'] == '0'){echo 'selected ';} ?>value="0"><?php echo $this->strNo; ?></option>
		      			<option <? if($this->formvars['start_aktiv'] == 1){echo 'selected ';} ?>value="1"><?php echo $this->strYes; ?></option>		      			
		      		</select>
		  		</td>
		  	</tr>
				<tr>
		    	<th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLogconsume; ?></th>
		    	<td colspan=2 style="border-bottom:1px solid #C3C7C3">
		      		<select name="logconsume">
		      			<option <? if($this->formvars['logconsume'] == '0'){echo 'selected ';} ?>value="0"><?php echo $this->strNo; ?></option>
		      			<option <? if($this->formvars['logconsume'] == 1){echo 'selected ';} ?>value="1"><?php echo $this->strYes; ?></option>		      			
		      		</select>
		  		</td>
		  	</tr>
		</table>
	</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr> 
    <td align="center">
    	<input type="button" value="<?php echo $this->strButtonBack; ?>" onclick="document.location.href='index.php?go=Layer2Stelle_Reihenfolge&selected_stelle_id=<? echo $this->formvars['selected_stelle_id']; ?>'">
    	<input type="hidden" name="selected_layer_id" value="<?php echo $this->formvars['selected_layer_id']; ?>">
    	<input type="hidden" name="selected_stelle_id" value="<?php echo $this->formvars['selected_stelle_id']; ?>">
    	<input type="hidden" name="stellen_name" value="<?php echo $this->formvars['stellen_name']; ?>">
    	<input type="hidden" name="go" value="Layer2Stelle_Editor">
    	<input type="hidden" name="go_plus" id="go_plus" value="">&nbsp;<input type="button" name="dummy" value="<?php echo $this->strSave; ?>" onclick="submitWithValue('GUI','go_plus','Speichern')">
	</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
</table>
