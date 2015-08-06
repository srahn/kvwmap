<?php
 # 2008-02-05 pkvvm
  include(LAYOUTPATH.'languages/MapCommentSelectForm_'.$this->user->rolle->language.'.php');
 ?>
<h2><?php echo $strTitleLayers; ?></h2>
<br>
<table cellpadding="0" cellspacing="0">
<?	$anzLayerComments=count($this->layerComments);
  for ($i=0;$i<$anzLayerComments;$i++) { ?>
		<tr>
			<td style="background-color:<? echo BG_GLEATTRIBUTE; ?>;border:1px solid #C3C7C3;"><a href="index.php?go=Layerauswahl_Laden&id=<? echo $this->layerComments[$i]['id']; ?>"><div style="padding:4px;"><span class="fett"><? echo $this->layerComments[$i]['name']; ?></span></div></a></td>
			<td>&nbsp;&nbsp;<a title="<? echo $this->strDelete; ?>" href="javascript:Bestaetigung('index.php?go=Layerauswahl_loeschen&id=<?php echo $this->layerComments[$i]['id']; ?>','<? echo $this->strDeleteWarningMessage; ?>');"><img style="vertical-align:middle; border: 1px solid #C3C7C3" src="<? echo GRAPHICSPATH ?>datensatz_loeschen.png"></a></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
<?		} ?>				
</table> 