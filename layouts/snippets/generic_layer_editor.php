
<script type="text/javascript">
<!--

//-->
</script>

<h2><? echo $this->qlayerset[$i]['Name'] ?></h2>
<?
  $anzObj = count($this->qlayerset[$i]['shape']);
  if ($anzObj > 0) {
  	$this->found = 'true';
  	$doit = true;
  }
  if($this->new_entry == true){
  	$anzObj = 1;
  	$doit = true;
  }
  if($doit == true){
?>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td>
			&nbsp;
		</td>
		<td>   
			<table border="1" cellspacing="0" cellpadding="2">
			  <tr bgcolor="<?php echo BG_DEFAULT ?>">
			  
			  <?
			  	for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
			  		if($this->qlayerset[$i]['attributes']['type'][$j] != 'geometry'){
			  			if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0'){
			  				if($this->qlayerset[$i]['attributes']['alias'][$j] != ''){
									echo '<td align="center"><font color="#6c6c6c"><b>'.$this->qlayerset[$i]['attributes']['alias'][$j].'</b></font></td>';
			  				}
			  				else{
			  					echo '<td align="center"><font color="#6c6c6c"><b>'.$this->qlayerset[$i]['attributes']['name'][$j].'</b></font></td>';
			  				}
							}
							else{
								if($this->qlayerset[$i]['attributes']['alias'][$j] != ''){
									echo '<td align="center"><b>'.$this->qlayerset[$i]['attributes']['alias'][$j].'</b></td>';
								}
								else{
									echo '<td align="center"><b>'.$this->qlayerset[$i]['attributes']['name'][$j].'</b></td>';
								}
							}
			  		}
			  	}
			  ?>
			  <td>
			  	&nbsp;
			  </td>
			 <!-- <td>
			  	&nbsp;
			  </td>--> 
			    
			  </tr>
			  <?php
			    for ($k=0;$k<$anzObj;$k++) {
			      ?>
			  <tr>
			  	<?
				  	for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
				  		if($this->qlayerset[$i]['attributes']['type'][$j] != 'geometry'){
				  			echo '<td>';
				  			
				  			if($this->qlayerset[$i]['attributes']['enumstring'][$j] != ''){
				  				if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0'){
										echo '<input readonly size="6" type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].'" value="'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'">';
									}
				  				else{
				  					echo '<select name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].'">';
				  					echo '<option value=""></option>';
										for($e = 0; $e < count($this->qlayerset[$i]['attributes']['enum_value'][$j]); $e++){
											echo '<option ';
											if($this->qlayerset[$i]['attributes']['enum_value'][$j][$e] == $this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]){
												echo 'selected ';
											}
											echo 'value="'.$this->qlayerset[$i]['attributes']['enum_value'][$j][$e].'">'.$this->qlayerset[$i]['attributes']['enum_output'][$j][$e].'</option>';
										}
										echo '</select>';
				  				}
				  			}
				  			else{	  			
									switch ($this->qlayerset[$i]['attributes']['form_element_type'][$j]){
										case 'Textfeld' : {
											echo '<textarea';
											if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0'){
												echo ' readonly';
											}
											echo ' rows="2" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].'">'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'</textarea>';
										}break;
										
										case 'Auswahlfeld' : {
											if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0'){
												echo '<input readonly size="6" type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].'" value="'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'">';
											}
											else{
												echo '<select name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].'">';
												echo '<option value=""></option>';
												for($e = 0; $e < count($this->qlayerset[$i]['attributes']['enum_value'][$j]); $e++){
													echo '<option ';
													if($this->qlayerset[$i]['attributes']['enum_value'][$j][$e] == $this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]){
														echo 'selected ';
													}
													echo 'value="'.$this->qlayerset[$i]['attributes']['enum_value'][$j][$e].'">'.$this->qlayerset[$i]['attributes']['enum_output'][$j][$e].'</option>';
												}
												echo '</select>';
											}
										}break;
										
										default : {
											echo '<input';
											if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0'){
												echo ' readonly';
											}
											echo ' size="6" type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].'" value="'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'">';
										}
									}
				  			}
								if($this->qlayerset[$i]['attributes']['privileg'][$j] == 1){
									$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].'|';
								}
								echo '	
								</td>';
				  		}
				  		else{
				  			$columnname = $this->qlayerset[$i]['attributes']['name'][$j];
				  			$tablename = $this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]];
				  			$geomtype = $this->qlayerset[$i]['attributes']['geomtype'][$this->qlayerset[$i]['attributes']['name'][$j]];
				  			$dimension = $this->qlayerset[$i]['attributes']['dimension'][$j];
				  			$privileg = $this->qlayerset[$i]['attributes']['privileg'][$j];
				  		}
				  	}
				  if(($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON') AND $privileg == 1){
				  ?>
			    <td> 
			      <a href="index.php?go=PolygonEditor&oid=<?php echo $this->qlayerset[$i]['shape'][$k][$tablename.'_oid']; ?>&tablename=<? echo $tablename; ?>&columnname=<? echo $columnname; ?>&layer_id=<? echo $this->qlayerset[$i]['Layer_ID'];?>">Geometrie bearbeiten</a>
			    </td>
			    <?
				  }
				  elseif($geomtype == 'POINT' AND $privileg == 1){
			    ?>
			    <td> 
			      <a href="index.php?go=PointEditor&dimension=<? echo $dimension; ?>&oid=<?php echo $this->qlayerset[$i]['shape'][$k][$tablename.'_oid']; ?>&tablename=<? echo $tablename; ?>&columnname=<? echo $columnname; ?>&layer_id=<? echo $this->qlayerset[$i]['Layer_ID'];?>">Geometrie bearbeiten</a>
			    </td>
			    <?}?>
			 <!--   <td> 
			      <a href="javascript:Bestaetigung('index.php?go=jagdkatastereditor_Loeschen&oid=<?php echo $this->qlayerset[$i]['shape'][$k]['oid']; ?>', 'Wollen Sie diesen Jagdbezirk wirklich löschen?');">löschen</a>
			    </td>
			 --> </tr>
			  <?php
			    }
			    ?>
			</table>
		</td>
		<td>
			&nbsp;
		</td>
	</tr>
</table>
<br/>
<?php    
  }
  else {
    ?><br><strong><font color="#FF0000">
    Zu diesem Layer wurden keine Objekte gefunden!</font></strong><br>
    Wählen Sie einen neuen Bereich oder prüfen Sie die Datenquellen.<br>
    <?php   
  }
?>
