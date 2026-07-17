<?php
 # 2008-02-05 pkvvm
  include(LAYOUTPATH.'languages/MapCommentSelectForm_'.rolle::$language.'.php');
 ?>
<br>
<h2><?php echo $strTitleLayers; ?></h2>
<? if ($this->Stelle->is_admin_stelle()) {
	echo "<br>Adminstelle! Themenauswahlen aus allen Stellen und die, die keine user_id haben.<br>";
} ?>
<br>
<table id="LayerCommentSelectForm" cellpadding="0" cellspacing="0">
<?	$anzLayerComments = count_or_0($this->layerComments ?: []);
  for ($i=0;$i<$anzLayerComments;$i++) { ?>
		<tr>
			<td class="layer_comment_select_name_field">
				<a href="index.php?go=Layerauswahl_Laden&id=<? echo $this->layerComments[$i]['id']; ?>">
					<div class="layer_comment_select_name_div">
						<span class="fett"><? echo $this->layerComments[$i]['name']; ?></span><?
						if ($this->Stelle->is_admin_stelle()) {
							echo ' (stelle_id: ' . $this->layerComments[$i]['stelle_id']. ', user_id: ' . ($this->layerComments[$i]['user_id'] != '' ? $this->layerComments[$i]['user_id'] : ' keine') . ')';
						} ?>
					</div>
				</a>
			</td>
			<td class="layer_comment_select_func">
				<a href="javascript:void(0)" title="Themenauswahl teilen" onclick="showURL('go=Layerauswahl_Laden&id=<? echo $this->layerComments[$i]['id']; ?>&user_id=<? echo $this->user->id; ?>', 'Link zu dieser Themenauswahl')">
					<i class="fa fa-share-alt"></i>
				</a>
			</td><?
			if ($this->layerComments[$i]['user_id'] != '' OR $this->Stelle->is_admin_stelle()) { ?>
				<td class="layer_comment_select_func">
					<a title="<? echo $this->strDelete; ?>" href="javascript:Bestaetigung('index.php?go=Layerauswahl_loeschen&id=<?php echo $this->layerComments[$i]['id']; ?>','<? echo $this->strDeleteWarningMessage; ?>');">
						<img style="vertical-align:middle;" src="<? echo GRAPHICSPATH ?>datensatz_loeschen.png">
					</a>
				</td><?
			} ?>
		</tr>
		<tr><td>&nbsp;</td></tr>
<?		} ?>				
</table> 