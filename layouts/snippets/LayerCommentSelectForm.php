<?php
 # 2008-02-05 pkvvm
  include(LAYOUTPATH.'languages/MapCommentSelectForm_'.rolle::$language.'.php');
 ?>
<br>
<h2><?php echo $strTitleLayers; ?></h2>
<br>
<table cellpadding="0" cellspacing="0">
<?	$anzLayerComments = count_or_0($this->layerComments ?: []);
  for ($i=0;$i<$anzLayerComments;$i++) { ?>
		<tr>
			<td style="background-color:<? echo BG_GLEATTRIBUTE; ?>;border:1px solid #C3C7C3;">
				<a href="index.php?go=Layerauswahl_Laden&id=<? echo $this->layerComments[$i]['id']; ?>">
					<div style="padding:4px;">
						<span class="fett"><? echo $this->layerComments[$i]['name']; ?></span>
					</div>
				</a>
			</td>
			<td>
			&nbsp;&nbsp;
				<a href="javascript:void(0)" title="Themenauswahl teilen" onclick="showURL('go=Layerauswahl_Laden&id=<? echo $this->layerComments[$i]['id']; ?>&user_id=<? echo $this->user->id; ?>', 'Link zu dieser Themenauswahl')">
					<i class="fa fa-share-alt"></i>
				</a>
			</td>
			<td>
				&nbsp;&nbsp;
				<a title="<? echo $this->strDelete; ?>" href="javascript:Bestaetigung('index.php?go=Layerauswahl_loeschen&id=<?php echo $this->layerComments[$i]['id']; ?>','<? echo $this->strDeleteWarningMessage; ?>');">
					<img style="vertical-align:middle;" src="<? echo GRAPHICSPATH ?>datensatz_loeschen.png">
				</a>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
<?		} ?>				
</table> 