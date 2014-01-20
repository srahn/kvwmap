
<script type="text/javascript">
<!--

function georg(){
	top.document.GUI.go.value = 'georg_export';
	top.document.GUI.submit();
}

function popup(id){
	if(document.getElementById(id).style.display == 'none'){
		document.getElementById(id).style.display = '';
	}
	else{
		document.getElementById(id).style.display = 'none';
	}
}


//-->
</script>

<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center">
    <td colspan="2"><strong><font size="+1"><br>
      <?php echo $this->titel; ?></font></strong></td>
  </tr>
  <tr>
    <td>
    	<table width="100%" border="0" cellspacing="0" cellpadding="2">
	    <?php
			if ($this->formvars['zeitraum']=='month') { ?>
				<tr>
	      	<td colspan="2"><br><b>Monat: <?php echo $this->formvars['month_m']; ?>/<?php echo $this->formvars['year_m']; ?></b><br></td>
				</tr>
			<? }elseif($this->formvars['zeitraum']=='week'){ ?>
				<tr>
					<td colspan="2"><b><br>Woche: <?php echo $this->formvars['week_w']; ?>. Kalenderwoche <?php echo $this->formvars['year_w'] ?></b><br></td>
				</tr>
			<? }elseif($this->formvars['zeitraum']=='day'){ ?>
				<tr>
	      	<td colspan="2"><b>Tag: <?php echo $this->formvars['day_d'].'.'.$this->formvars['month_d'].'.'.$this->formvars['year_d']; ?></b><br></td>
	      </tr>
	    <? }elseif($this->formvars['zeitraum']=='era'){ ?>
	    	<tr>
	      	<td colspan="2"><b>Zeitraum - vom: <?php echo $this->formvars['day_e1'].'.'.$this->formvars['month_e1'].'.'.$this->formvars['year_e1']; ?> bis  <?php echo $this->formvars['day_e2'].'.'.$this->formvars['month_e2'].'.'.$this->formvars['year_e2']; ?></b><br></td>
	      </tr>
	    <? } ?>
	    	<?php if ($this->formvars['nutzung']=='stelle') { ?>
		    <tr>
	      	<td colspan="2"><li>Zugriffe der Stelle: <?php echo $this->account->Bezeichnung;?></li></td>
	      </tr>
	      <? }elseif($this->formvars['nutzung']=='nutzer'){ ?>
	    	<tr>
	        <td colspan="2"><li>Zugriffe durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?> </li></td>
	      <tr>
	      <? }elseif($this->formvars['nutzung']=='stelle_nutzer' ){ ?>
	      <tr>
		      <td colspan="2"><li>Zugriffe durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?> über
		          die Stelle: <?php echo $this->account->Bezeichnung ;?></li>
		      </td>
		    </tr>
		    <? } ?>
	      <tr>
	      	<td colspan="2"><ul>
	        	<table width="90%" border="1" cellspacing="3" cellpadding="0">
	        		<tr>
	            	<td>
	            		<table width="100%" border="0" cellpadding="5" cellspacing="0">
	                	<tr bgcolor="#FFFFFF">
	                  	<td width="50%"><strong>Layer</strong></td>
	                    <td width="50%"><strong>Anzahl der Zugriffe</strong></td>
	                  </tr>
	                  <tr bgcolor="#FFFFFF">
	                  	<td colspan="13"><hr></td>
	                  </tr>
					          <?php
										$accessarray=$this->account->NumbOfAccess;
										if(count($this->account->NumbOfAccess)==0){ ?>
	            			<tr>
	                  	<td colspan="2">
	                    	<?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
	                    </td>
	                  </tr>
	                 	<?php
										}
										else{
	                 		for($i=0;$i<count($this->account->NumbOfAccess);$i++) {
				  						?>
	                    	<tr bgcolor="<?php if ($i%2!=0) { echo 'FFFFFF'; } else { echo 'EBEBEB'; } ?>">
	                    		<td><?php echo $this->account->NumbOfAccess[$i]['lName']; ?></td>
	                      		<td><?php echo $this->account->NumbOfAccess[$i]['NumberOfAccess']; ?></td>
	                    	</tr>
	              	<? }
					      		}?>
	                </table>
	               </td>
	              </tr>
	             </table>
	            </ul>
	           </td>
	          </tr>

		     			<? if ($this->formvars['nutzung']=='stelle') { ?>
				    <tr>
			      	<td colspan="2"><li>ALK-Drucke der Stelle: <?php echo $this->account->Bezeichnung;?></li></td>
			      </tr>
			      <? }elseif($this->formvars['nutzung']=='nutzer'){ ?>
			    	<tr>
			        <td colspan="2"><li>ALK-Drucke durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?> </li></td>
			      <tr>
			      <? }elseif($this->formvars['nutzung']=='stelle_nutzer' ){ ?>
			      <tr>
				      <td colspan="2"><li>ALK-Drucke durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?> über
				          die Stelle: <?php echo $this->account->Bezeichnung ;?></li>
				      </td>
				    </tr>
				   <? } ?>
		        <tr>
		          <td colspan="2"><ul>
		              <table width="90%" border="1" cellspacing="3" cellpadding="0">
		                <tr>
		                <td>
		                	<table width="100%" border="0" cellpadding="5" cellspacing="0">
		                    <tr bgcolor="#FFFFFF">
		                      <td width="40%"><strong>Drucklayout</strong></td>
		                      <td width="27%"><strong>Format</strong></td>
		                      <td width="33%"><strong>Anzahl der ALK-Drucke</strong></td>
		                    </tr>
		                    <tr bgcolor="#FFFFFF">
		                      <td colspan="13"><hr></td>
		                    </tr>
		                    <?php
												//$accessarray=$this->account->ALKNumbOfAccessUserStelleM;
												if ( count($this->account->ALKNumbOfAccess)==0 ) { ?>
		                    	<tr>
		                      	<td align="center" colspan="2">
		                        	<?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
		                      	</td>
		                      <?php
												}
												else {
		          						for ($i=0;$i<count($this->account->ALKNumbOfAccess);$i++) {
					  							?>
		            					<tr bgcolor="<?php if ($i%2!=0) { echo 'FFFFFF'; } else { echo 'EBEBEB'; } ?>">
		                					<td><a href="javascript:popup('alk<? echo $i; ?>');"><?php echo $this->account->ALKNumbOfAccess[$i]['druckrahmenname']; ?></a></td>
		                					<td><?php echo $this->account->ALKNumbOfAccess[$i]['Druckformat']; ?></td>
		                					<td><?php echo $this->account->ALKNumbOfAccess[$i]['NumberOfAccess']; ?></td>
		              					</tr>
		              					<tr id="alk<? echo $i; ?>" style="display:none">
		              						<td colspan="3">
		              							<table>
		              								<? for($j = 0; $j < count($this->account->ALKNumbOfAccess[$i]['time_ids']); $j++){ ?>
		              								<tr>
		              									<td><? echo $this->account->ALKNumbOfAccess[$i]['time_ids'][$j]['time_id']; ?></td>
		              									<td><? echo $this->account->ALKNumbOfAccess[$i]['time_ids'][$j]['Name']; ?></td>
		              								</tr>
		              								<? } ?>
		              							</table>
		              						</td>
		              					</tr>
		            				<? } ?>
		            			<? } ?>
		            		</table>
		          		</td>
		          		</tr>
		         		</table>
		       		</ul>
		     			</td>
		     			</tr>

		     			<? if ($this->formvars['nutzung']=='stelle') { ?>
				    <tr>
			      	<td colspan="2"><li>ALB-Drucke der Stelle: <?php echo $this->account->Bezeichnung;?></li></td>
			      </tr>
			      <? }elseif($this->formvars['nutzung']=='nutzer'){ ?>
			    	<tr>
			        <td colspan="2"><li>ALB-Drucke durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?> </li></td>
			      <tr>
			      <? }elseif($this->formvars['nutzung']=='stelle_nutzer' ){ ?>
			      <tr>
				      <td colspan="2"><li>ALB-Drucke durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?> über
				          die Stelle: <?php echo $this->account->Bezeichnung ;?></li>
				      </td>
				    </tr>
				   <? } ?>
		        <tr>
		          <td colspan="2"><ul>
		              <table width="90%" border="1" cellspacing="3" cellpadding="0">
		                <tr>
		                <td>
		                	<table width="100%" border="0" cellpadding="5" cellspacing="0">
		                    <tr bgcolor="#FFFFFF">
		                      <td width="33%"><strong>ALB-Format</strong></td>
		                      <td width="33%"><strong>Anzahl der ALB-Drucke</strong></td>
		                      <td width="33%"><strong>Anzahl der Seiten</strong></td>
		                    </tr>
		                    <tr bgcolor="#FFFFFF">
		                      <td colspan="13"><hr></td>
		                    </tr>
		                    <?php
												//$accessarray=$this->account->ALKNumbOfAccessUserStelleM;
												if ( count($this->account->ALBNumbOfAccess)==0 ) { ?>
		                    	<tr>
		                      	<td align="center" colspan="2">
		                        	<?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
		                      	</td>
		                      <?php
												}
												else {
		          						for ($i=0;$i<count($this->account->ALBNumbOfAccess);$i++) {
					  							?>
		            					<tr bgcolor="<?php if ($i%2!=0) { echo 'FFFFFF'; } else { echo 'EBEBEB'; } ?>">
		                					<td><a href="javascript:popup('alb<? echo $i; ?>');"><?php echo $this->account->ALBNumbOfAccess[$i]['format']; ?></a></td>
		                					<td><?php echo $this->account->ALBNumbOfAccess[$i]['NumberOfAccess']; ?></td>
		                					<td><?php echo $this->account->ALBNumbOfAccess[$i]['pages']; ?></td>
		              					</tr>
		              					<tr id="alb<? echo $i; ?>" style="display:none">
		              						<td colspan="3">
		              							<table>
		              								<? for($j = 0; $j < count($this->account->ALBNumbOfAccess[$i]['time_ids']); $j++){ ?>
		              								<tr>
		              									<td><? echo $this->account->ALBNumbOfAccess[$i]['time_ids'][$j]['time_id']; ?></td>
		              									<td><? echo $this->account->ALBNumbOfAccess[$i]['time_ids'][$j]['Name']; ?></td>
		              								</tr>
		              								<? } ?>
		              							</table>
		              						</td>
		              					</tr>
		            				<? } ?>
		            			<? } ?>
		            		</table>
		          		</td>
		          		</tr>
		         		</table>
		       		</ul>
		     			</td>
		     			</tr>
		     			
		     				<? if ($this->formvars['nutzung']=='stelle') { ?>
				    <tr>
			      	<td colspan="2"><li>CSV-Exporte der Stelle: <?php echo $this->account->Bezeichnung;?></li></td>
			      </tr>
			      <? }elseif($this->formvars['nutzung']=='nutzer'){ ?>
			    	<tr>
			        <td colspan="2"><li>CSV-Exporte durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?> </li></td>
			      <tr>
			      <? }elseif($this->formvars['nutzung']=='stelle_nutzer' ){ ?>
			      <tr>
				      <td colspan="2"><li>CSV-Exporte durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?> über
				          die Stelle: <?php echo $this->account->Bezeichnung ;?></li>
				      </td>
				    </tr>
				   <? } ?>
		        <tr>
		          <td colspan="2"><ul>
		              <table width="90%" border="1" cellspacing="3" cellpadding="0">
		                <tr>
		                <td>
		                	<table width="100%" border="0" cellpadding="5" cellspacing="0">
		                    <tr bgcolor="#FFFFFF">
		                      <td width="33%"><strong>Export-Art</strong></td>
		                      <td width="33%"><strong>Anzahl der Exporte</strong></td>
		                      <td width="33%"><strong>Anzahl der Datensätze</strong></td>
		                    </tr>
		                    <tr bgcolor="#FFFFFF">
		                      <td colspan="13"><hr></td>
		                    </tr>
		                    <?php
												//$accessarray=$this->account->ALKNumbOfAccessUserStelleM;
												if ( count($this->account->CSVNumbOfAccess)==0 ) { ?>
		                    	<tr>
		                      	<td align="center" colspan="2">
		                        	<?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
		                      	</td>
		                      <?php
												}
												else {
		          						for ($i=0;$i<count($this->account->CSVNumbOfAccess);$i++) {
					  							?>
		            					<tr bgcolor="<?php if ($i%2!=0) { echo 'FFFFFF'; } else { echo 'EBEBEB'; } ?>">
		                					<td><a href="javascript:popup('csv<? echo $i; ?>');"><?php echo $this->account->CSVNumbOfAccess[$i]['art']; ?></a></td>
		                					<td><?php echo $this->account->CSVNumbOfAccess[$i]['NumberOfAccess']; ?></td>
		                					<td><?php echo $this->account->CSVNumbOfAccess[$i]['datasets']; ?></td>
		              					</tr>
		              					<tr id="csv<? echo $i; ?>" style="display:none">
		              						<td colspan="3">
		              							<table>
		              								<? for($j = 0; $j < count($this->account->CSVNumbOfAccess[$i]['time_ids']); $j++){ ?>
		              								<tr>
		              									<td><? echo $this->account->CSVNumbOfAccess[$i]['time_ids'][$j]['time_id']; ?></td>
		              									<td><? echo $this->account->CSVNumbOfAccess[$i]['time_ids'][$j]['Name']; ?></td>
		              								</tr>
		              								<? } ?>
		              							</table>
		              						</td>
		              					</tr>
		            				<? } ?>
		            			<? } ?>
		            		</table>
		          		</td>
		          		</tr>
		         		</table>
		       		</ul>
		     			</td>
		     			</tr>
		     			
		     			
		     		<? if ($this->formvars['nutzung']=='stelle') { ?>
				    <tr>
			      	<td colspan="2"><li>Shape-Exporte der Stelle: <?php echo $this->account->Bezeichnung;?></li></td>
			      </tr>
			      <? }elseif($this->formvars['nutzung']=='nutzer'){ ?>
			    	<tr>
			        <td colspan="2"><li>Shape-Exporte durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?> </li></td>
			      <tr>
			      <? }elseif($this->formvars['nutzung']=='stelle_nutzer' ){ ?>
			      <tr>
				      <td colspan="2"><li>Shape-Exporte durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?> über
				          die Stelle: <?php echo $this->account->Bezeichnung ;?></li>
				      </td>
				    </tr>
				   <? } ?>
		        <tr>
		          <td colspan="2"><ul>
		              <table width="90%" border="1" cellspacing="3" cellpadding="0">
		                <tr>
		                <td>
		                	<table width="100%" border="0" cellpadding="5" cellspacing="0">
		                    <tr bgcolor="#FFFFFF">
		                      <td width="33%"><strong>Layer-ID</strong></td>
		                      <td width="33%"><strong>Anzahl der Exporte</strong></td>
		                      <td width="33%"><strong>Anzahl der Datensätze</strong></td>
		                    </tr>
		                    <tr bgcolor="#FFFFFF">
		                      <td colspan="13"><hr></td>
		                    </tr>
		                    <?php
												//$accessarray=$this->account->ALKNumbOfAccessUserStelleM;
												if ( count($this->account->ShapeNumbOfAccess)==0 ) { ?>
		                    	<tr>
		                      	<td align="center" colspan="2">
		                        	<?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
		                      	</td>
		                      <?php
												}
												else {
		          						for ($i=0;$i<count($this->account->ShapeNumbOfAccess);$i++) {
					  							?>
		            					<tr bgcolor="<?php if ($i%2!=0) { echo 'FFFFFF'; } else { echo 'EBEBEB'; } ?>">
		                					<td><a href="javascript:popup('Shape<? echo $i; ?>');"><?php echo $this->account->ShapeNumbOfAccess[$i]['layer_id']; ?></a></td>
		                					<td><?php echo $this->account->ShapeNumbOfAccess[$i]['NumberOfAccess']; ?></td>
		                					<td><?php echo $this->account->ShapeNumbOfAccess[$i]['datasets']; ?></td>
		              					</tr>
		              					<tr id="Shape<? echo $i; ?>" style="display:none">
		              						<td colspan="3">
		              							<table>
		              								<? for($j = 0; $j < count($this->account->ShapeNumbOfAccess[$i]['time_ids']); $j++){ ?>
		              								<tr>
		              									<td><? echo $this->account->ShapeNumbOfAccess[$i]['time_ids'][$j]['time_id']; ?></td>
		              									<td><? echo $this->account->ShapeNumbOfAccess[$i]['time_ids'][$j]['Name']; ?></td>
		              								</tr>
		              								<? } ?>
		              							</table>
		              						</td>
		              					</tr>
		            				<? } ?>
		            			<? } ?>
		            		</table>
		          		</td>
		          		</tr>
		         		</table>
		       		</ul>
		     			</td>
		     			</tr>
		     			
		     			
		     			<!--tr>
		     				<td colspan="2" align="center"><input type="button" name="georg_export" value="Georg-Datei erzeugen" onclick="georg();"></td>
		     			</tr-->

						    <td align="center" colspan="2"> <input name="zurueck" type="submit" value="Zurück"> </td>
						  </tr>
						  <tr>
						    <td colspan="2" align="center">&nbsp;</td>
						  </tr>
						</table>
<!--					<br>
						<table align="center" width="400" height="300" border="1" cellpadding="1" cellspacing="1">
						  <tr>
						    <td align="center" bordercolor="#000000" bgcolor="#FFFFFF" ><?php include ('chart.php'); ?></td>
						  </tr>
						</table>

<br>
<br>
<input name="zurueck" type="submit" value="Zurück">
<br><br>
-->

		</td>
	</tr>
</table>

<input type="hidden" name="go" value="<? echo $this->formvars['go']; ?>">
  <input type="hidden" name="order" value="<?php echo $this->formvars['order']; ?>">
  <input type="hidden" name="nutzer" value="<?php echo $this->formvars['nutzer']; ?>">
  <input type="hidden" name="nutzung" value="<?php echo $this->formvars['nutzung']; ?>">
  <input type="hidden" name="stelle" value="<?php echo $this->formvars['stelle']; ?>">
  <input type="hidden" name="zeitraum" value="<?php echo $this->formvars['zeitraum']; ?>">
  <input type="hidden" name="month_d" value="<?php echo $this->formvars['month_d']; ?>">
  <input type="hidden" name="month_w" value="<?php echo $this->formvars['month_w']; ?>">
  <input type="hidden" name="month_m" value="<?php echo $this->formvars['month_m']; ?>">
  <input type="hidden" name="year_m" value="<?php echo $this->formvars['year_m']; ?>">
  <input type="hidden" name="year_w" value="<?php echo $this->formvars['year_w']; ?>">
  <input type="hidden" name="year_d" value="<?php echo $this->formvars['year_d']; ?>">
  <input type="hidden" name="day_d" value="<?php echo $this->formvars['day_d']; ?>">
  <input type="hidden" name="week_w" value="<?php echo $this->formvars['week_w']; ?>">
  <input type="hidden" name="day_e1" value="<?php echo $this->formvars['day_e1']; ?>">
  <input type="hidden" name="day_e2" value="<?php echo $this->formvars['day_e2']; ?>">
  <input type="hidden" name="month_e1" value="<?php echo $this->formvars['month_e1']; ?>">
  <input type="hidden" name="month_e2" value="<?php echo $this->formvars['month_e2']; ?>">
  <input type="hidden" name="year_e1" value="<?php echo $this->formvars['year_e1']; ?>">
  <input type="hidden" name="year_e2" value="<?php echo $this->formvars['year_e2']; ?>">
  <input type="hidden" name="chart" value="<?php echo $this->formvars['chart']; ?>">
  <input type="hidden" name="bezeichnung" value="<?php echo $this->account->Bezeichnung; ?>">
  <input type="hidden" name="anzahlA4" value="<? echo $this->account->ALKA4; ?>">
  <input type="hidden" name="anzahlA3" value="<? echo $this->account->ALKA3; ?>">
  <input type="hidden" name="anzahlALB" value="<? echo $this->account->ALB; ?>">

