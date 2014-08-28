<?php
 # 2008-01-12 pkvvm
  include(LAYOUTPATH.'languages/layer_export_'.$this->user->rolle->language.'.php');
 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

	function export_layer(){
		document.GUI.selected_layers.value = '';
		addSelectedValuesToHiddenField(document.GUI.layer, document.GUI.selected_layers);
		document.GUI.go.value = 'Layer_Export_Exportieren';
		document.GUI.submit();
	}
  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="5"><h2><?php echo $this->titel; ?></h2></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr> 
    <td style="border-top:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan="5"><?php echo $strLayer;?></td>
  </tr>
  <tr> 
    <td style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3" colspan="5"> 
      <select multiple="multiple" style="width:250px" size="20"  name="layer"  <?php if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
        <?
    		for($i = 0; $i < count($this->layerdaten['ID']); $i++){
    			echo '<option';
    			if($this->layerdaten['ID'][$i] == $this->formvars['selected_layer_id']){
    				echo ' selected';
    			}
    			echo ' value="'.$this->layerdaten['ID'][$i].'">'.$this->layerdaten['Bezeichnung'][$i].'</option>';
    		}
    	?>
      </select> </td>
  </tr>
  <tr> 
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
  	<td><input type="button" class="button" value="Exportieren" onclick="export_layer();"></td>
  </tr>
  <tr> 
    <td colspan="5" >&nbsp;</td>
  </tr>
  <? if($this->layer_dumpfile != ''){ ?>
  <tr>
  	<td>Layer wurden exportiert. <a href="<? echo TEMPPATH_REL.$this->layer_dumpfile; ?>"><span class="fett">Herunterladen</span></a></td>
  </tr>	
  <? } ?>
</table>

<input type="hidden" name="go" value="Layer_Export">
<input type="hidden" name="selected_layers" value="">


