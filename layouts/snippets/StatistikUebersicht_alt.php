<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="2"><h2><br>
      <?php echo $this->titel; ?></h2></td>
  </tr>
  <tr> 
    <td> <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr> 
          <td colspan="2"><hr color="#000000" size="2"></td>
        <tr> 
          <td colspan="2">Zeitraum f&uuml;r Auswertung: von <?php echo $this->account->epoch['min_d'].'-'.$this->account->epoch['min_m'].'-'.$this->account->epoch['min_y']; ?> 
            bis <?php echo $this->account->epoch['max_d'].'-'.$this->account->epoch['max_m'].'-'.$this->account->epoch['max_y']; ?> 
          </td>
        <tr> 
          <td colspan="2"><hr color="#000000" size="1"></td>
        <tr> 
          <td colspan="2"><span class="fett">Zusammenfassung: </span></td>
        <tr> 
          <td width="50%"><li>Anzahl der Layer :</li></td>
          <td width="50%"><?php echo $this->account->getLayer['layers']; ?></td>
		<tr> 
          <td width="50%"><li>Anzahl der geloggten Layer :</li></td>
          <td width="50%"><?php echo $this->account->getLoggedLayer['layers']; ?></td>
        <tr> 
          <td width="50%"><li>Anzahl der Zugriffe auf alle Layer:</li></td>
          <td width="50%"><?php echo $this->account->allLayerAccess['allAccess']; ?></td>
        <tr> 
          <td width="50%"><li>Anzahl aller Zugriffe des Kartenfensters:</li></td>
          <td width="50%"><?php echo $this->account->allAccess['allAccess']; ?></td>
		<tr> 
          <td colspan="2" align="center"><hr color="#00000" size="1"></td></tr>

     	<?php 
		if ($this->formvars['zeitraum']=='month') { ?>
		<tr>       
          <td colspan="2"><br><span class="fett">Monat: <?php echo $this->formvars['month_m']; ?>/<?php echo $this->formvars['year_m']; ?></span><br></td>
    
		<?php if ($this->formvars['nutzung']=='stelle') { ?>

	    <tr> 
          <td colspan="2"><li>Zugriffe der Stelle: <?php echo $this->account->Bezeichnung ;?>
            </li></td>
        <tr> 
          <td colspan="2"><ul>
              <table width="90%" border="1" cellspacing="0" cellpadding="0">
                <td> <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr bgcolor="#FFFFFF"> 
                      <td width="50%"><div align="center"><strong>Layer</strong></div></td>
                      <td width="50%"><div align="center"><strong>Anzahl der Zugriffe</strong></div></td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td colspan="13"><hr></td>
                    </tr>
                    <?php
					$accessarray=$this->account->NumbOfAccessStelleM;
					if ( count($this->account->NumbOfAccessStelleM)==0 ) { ?>
                    <tr> 
                      <td align="center" colspan="2">
                        <?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
                      </td>
                      <?php
					}
					else {
                 for ($i=0;$i<count($this->account->NumbOfAccessStelleM);$i++) {
			  ?>
                    <tr bgcolor="<?php if ($i%2!=0) {
				     echo '#FFFFFF';
                   }
                   else {
                     echo '#EBEBEB';}?>"> 
                      <td><div align="center"><?php echo $this->account->NumbOfAccessStelleM[$i]['lName']; ?></div></td>
                      <td><div align="center"><?php echo $this->account->NumbOfAccessStelleM[$i]['NumberOfAccess']; ?></div></td>
                    </tr>
                    <?php } 
				      }?>
                  </table></td>
              </table>
            </ul></td>
				  
          <?php } 
          
          if ($this->formvars['nutzung']=='nutzer') {   ?>
		  
        <tr> 
          <td colspan="2"><li>Zugriffe durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?> </li></td>
        <tr> 
          <td colspan="2"><ul>
              <table width="90%" border="1" cellspacing="0" cellpadding="0">
                <td> <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr bgcolor="#FFFFFF"> 
                      <td width="50%"><div align="center"><strong>Layer</strong></div></td>
                      <td width="50%"><div align="center"><strong>Anzahl der Zugriffe</strong></div></td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td colspan="13"><hr></td>
                    </tr>
					<?php
					$accessarray=$this->account->NumbOfAccessUserM;
					if ( count($this->account->NumbOfAccessUserM)==0 ) { ?>
                    <tr> 
                      <td align="center" colspan="3">
                        <?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
                      </td>
                      <?php
					}
					else {
                 for ($i=0;$i<count($this->account->NumbOfAccessUserM);$i++) {
			  ?>
                    <tr bgcolor="<?php if ($i%2!=0) {
				     echo '#FFFFFF';
                   }
                   else {
                     echo '#EBEBEB';}?>"> 
                      <td><div align="center"><?php echo $this->account->NumbOfAccessUserM[$i]['lName']; ?></div></td>
                      <td><div align="center"><?php echo $this->account->NumbOfAccessUserM[$i]['NumberOfAccess']; ?></div></td>
                    </tr>
                    <?php
           		}
			  }
							
         ?>
                  </table></td>
              </table>
            </ul></td>
         <?php } 
         
        if ($this->formvars['nutzung']=='stelle_nutzer' ) {     ?>
	        <tr> 
	          <td colspan="2"><li>Layer-Zugriffe durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?> über 
	              die Stelle: <?php echo $this->account->Bezeichnung ;?></li>
	          </td>
	        </tr>
	        <tr> 
	          <td colspan="2"><ul>
	              <table width="90%" border="1" cellspacing="0" cellpadding="0">
	                <td> <table width="100%" border="0" cellpadding="0" cellspacing="0">
	                    <tr bgcolor="#FFFFFF"> 
	                      <td width="50%"><div align="center"><strong>Layer</strong></div></td>
	                      <td width="50%"><div align="center"><strong>Anzahl der Zugriffe</strong></div></td>
	                    </tr>
	                    <tr bgcolor="#FFFFFF"> 
	                      <td colspan="13"><hr></td>
	                    </tr>
	                    <?php
						$accessarray=$this->account->NumbOfAccessUserStelleM;
						if ( count($this->account->NumbOfAccessUserStelleM)==0 ) { ?>
	                    <tr> 
	                      <td align="center" colspan="2">
	                        <?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
	                      </td>
	                      <?php
						}
						else {
	          	for ($i=0;$i<count($this->account->NumbOfAccessUserStelleM);$i++) {
				  		?>
	            	<tr bgcolor="<?php 
	            		if ($i%2!=0) {
					     			echo '#FFFFFF';
	                }
	                else{
	                	echo '#EBEBEB';
	                }?>">
	                <td><div align="center"><?php echo $this->account->NumbOfAccessUserStelleM[$i]['lName']; ?></div></td>
	                <td><div align="center"><?php echo $this->account->NumbOfAccessUserStelleM[$i]['NumberOfAccess']; ?></div></td>
	              </tr>
	            <?php } ?>
	            </table>
	          	</td>
	         		</table>
	       		</ul>
	     			</td>
	     			</tr>
	  				<?php  }?>
	  				
	 	
	  				
	  				<tr> 
	          <td colspan="2"><li>Drucke durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?> über 
	              die Stelle: <?php echo $this->account->Bezeichnung ;?></li>
	          </td>
	        </tr>
	        <tr> 
	          <td colspan="2"><ul>
	              <table width="90%" border="1" cellspacing="0" cellpadding="0">
	                <td> <table width="100%" border="0" cellpadding="0" cellspacing="0">
	                    <tr bgcolor="#FFFFFF"> 
	                      <td width="50%"><div align="center"><strong>Druckrahmen</strong></div></td>
	                      <td width="50%"><div align="center"><strong>Anzahl der Zugriffe</strong></div></td>
	                    </tr>
	                    <tr bgcolor="#FFFFFF"> 
	                      <td colspan="13"><hr></td>
	                    </tr>
	                    <?php
						//$accessarray=$this->account->ALKNumbOfAccessUserStelleM;
						if ( count($this->account->ALKNumbOfAccessUserStelleM)==0 ) { ?>
	                    <tr> 
	                      <td align="center" colspan="2">
	                        <?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
	                      </td>
	                      <?php
						}
						else {
	          	for ($i=0;$i<count($this->account->ALKNumbOfAccessUserStelleM);$i++) {
				  		?>
	            	<tr bgcolor="<?php 
	            		if ($i%2!=0) {
					     			echo '#FFFFFF';
	                }
	                else{
	                	echo '#EBEBEB';
	                }?>">
	                <td><div align="center"><?php echo $this->account->ALKNumbOfAccessUserStelleM[$i]['druckrahmenname']; ?></div></td>
	                <td><div align="center"><?php echo $this->account->ALKNumbOfAccessUserStelleM[$i]['NumberOfAccess']; ?></div></td>
	              </tr>
	            <?php } ?>
	            </table>
	          	</td>
	         		</table>
	       		</ul>
	     			</td>
	     			</tr>
	  				<?php  } ?>
	  				
			  		<?}  ?>
	        <tr>             
        
    <?php } ?>

       	<?php if ($this->formvars['zeitraum']=='week') { ?>		

          <td colspan="2"><span class="fett"><br>
            Woche: <?php echo $this->formvars['week_w']; ?>. Kalenderwoche <?php echo $this->formvars['year_w'] ?></span><br></td>
			
		<?php if ($this->formvars['nutzung']=='stelle') { ?>
		
        <tr> 
          <td colspan="2"><li>Zugriff der Stelle: <?php echo $this->account->Bezeichnung ;?></li></td>
        <tr> 
          <td colspan="2"><ul>
              <table width="90%" border="1" cellspacing="0" cellpadding="0">
                <td> <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr bgcolor="#FFFFFF"> 
                      <td width="50%"><div align="center"><strong>Layer</strong></div></td>
                      <td width="50%"><div align="center"><strong>Anzahl der Zugriffe</strong></div></td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td colspan="13"><hr></td>
                    </tr>
                    <?php
					$accessarray=$this->account->NumbOfAccessStelleW;
					if ( count($this->account->NumbOfAccessStelleW)==0 ) { ?>
                    <tr> 
                      <td align="center" colspan="3">
                        <?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
                      </td>
                      <?php
					}
					else {
                 for ($i=0;$i<count($this->account->NumbOfAccessStelleW);$i++) {
			  ?>
                    <tr bgcolor="<?php if ($i%2!=0) {
				     echo '#FFFFFF';
                   }
                   else {
                     echo '#EBEBEB';}?>"> 
                      <td><div align="center"><?php echo $this->account->NumbOfAccessStelleW[$i]['lName']; ?></div></td>
                      <td><div align="center"><?php echo $this->account->NumbOfAccessStelleW[$i]['NumberOfAccess']; ?></div></td>
                    </tr>
                    <?php
           }
		   }
         ?>
                  </table></td>
              </table>
            </ul></td>
		<?php } 
            
			if ($this->formvars['nutzung']=='nutzer') { 
		?>
        <tr> 
          <td colspan="2"><li>Zugriff durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?></li></td>
        <tr> 
          <td colspan="2"><ul>
              <table width="90%" border="1" cellspacing="0" cellpadding="0">
                <td> <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr bgcolor="#FFFFFF"> 
                      <td width="50%"><div align="center"><strong>Layer</strong></div></td>
                      <td width="50%"><div align="center"><strong>Anzahl der Zugriffe</strong></div></td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td colspan="13"><hr></td>
                    </tr>
                    <?php
					$accessarray=$this->account->NumbOfAccessUserW;
					if ( count($this->account->NumbOfAccessUserW)==0 ) { ?>
                    <tr> 
                      <td align="center" colspan="3">
                        <?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
                      </td>
                      <?php
					}
					else {
                 for ($i=0;$i<count($this->account->NumbOfAccessUserW);$i++) {
			  ?>
                    <tr bgcolor="<?php if ($i%2!=0) {
				     echo '#FFFFFF';
                   }
                   else {
                     echo '#EBEBEB';}?>"> 
                      <td><div align="center"><?php echo $this->account->NumbOfAccessUserW[$i]['lName']; ?></div></td>
                      <td><div align="center"><?php echo $this->account->NumbOfAccessUserW[$i]['NumberOfAccess']; ?></div></td>
                    </tr>
                    <?php
           }
		   }
         ?>
                  </table></td>
              </table>
            </ul></td>
			<?php } 
			
		if ($this->formvars['nutzung']=='stelle_nutzer') { 
		       ?>
        <tr> 
          <td colspan="2"><li>Zugriffe durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?> über 
              die Stelle: <?php echo $this->account->Bezeichnung ;?></li></td>
        <tr> 
          <td colspan="2"><ul>
              <table width="90%" border="1" cellspacing="0" cellpadding="0">
                <td> <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr bgcolor="#FFFFFF"> 
                      <td width="50%"><div align="center"><strong>Layer</strong></div></td>
                      <td width="50%"><div align="center"><strong>Anzahl der Zugriffe</strong></div></td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td colspan="13"><hr></td>
                    </tr>
                    <?php
					$accessarray=$this->account->NumbOfAccessUserStelleW;
					if ( count($this->account->NumbOfAccessUserStelleW)==0 ) { ?>
                    <tr> 
                      <td align="center" colspan="3">
                        <?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
                      </td>
                      <?php
					}
					else {
                 for ($i=0;$i<count($this->account->NumbOfAccessUserStelleW);$i++) {
			  ?>
                    <tr bgcolor="<?php if ($i%2!=0) {
				     echo '#FFFFFF';
                   }
                   else {
                     echo '#EBEBEB';}?>"> 
                      <td><div align="center"><?php echo $this->account->NumbOfAccessUserStelleW[$i]['lName']; ?></div></td>
                      <td><div align="center"><?php echo $this->account->NumbOfAccessUserStelleW[$i]['NumberOfAccess']; ?></div></td>
                    </tr>
                    <?php
           }
		   }
         ?>
                  </table></td>
              </table>
            </ul></td>
          <?php }
		  }
		  ?>			
       <?php if ($this->formvars['zeitraum']=='day') { ?>
        <tr> 
          <td colspan="2"><span class="fett">Tag: <?php echo $this->formvars['day_d'].'.'.$this->formvars['month_d'].'.'.$this->formvars['year_d']; ?></span><br>
           </td>
		  <?php if ($this->formvars['nutzung']=='stelle') { ?>
        <tr> 
          <td colspan="2">&nbsp;</td>
        <tr> 
          <td colspan="2"><li>Zugriff der Stelle: <?php echo $this->account->Bezeichnung ;?></li></td>
        <tr> 
          <td colspan="2"><ul>
              <table width="90%" border="1" cellspacing="0" cellpadding="0">
                <td> <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr bgcolor="#FFFFFF"> 
                      <td width="50%"><div align="center"><strong>Layer</strong></div></td>
                      <td width="50%"><div align="center"><strong>Anzahl der Zugriffe</strong></div></td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td colspan="13"><hr></td>
                    </tr>
                    <?php
					if ( count($this->account->NumbOfAccessStelleD)==0 ) { ?>
                    <tr> 
                      <td align="center" colspan="3">
                        <?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
                      </td>
                      <?php
					}
					else {
                 for ($i=0;$i<count($this->account->NumbOfAccessStelleD);$i++) {
			  ?>
                    <tr bgcolor="<?php if ($i%2!=0) {
				     echo '#FFFFFF';
                   }
                   else {
                     echo '#EBEBEB';}?>"> 
                      <td><div align="center"><?php echo $this->account->NumbOfAccessStelleD[$i]['lName']; ?></div></td>
                      <td><div align="center"><?php echo $this->account->NumbOfAccessStelleD[$i]['NumberOfAccess']; ?></div></td>
                    </tr>
                    <?php
           }
		   }
         ?>
                  </table></td>
              </table>
            </ul></td>
			<?php } 
			if ($this->formvars['nutzung']=='nutzer') { ?>
        <tr> 
          <td colspan="2"><li>Zugriff durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?></li></td>
        <tr> 
          <td colspan="2"><ul>
              <table width="90%" border="1" cellspacing="0" cellpadding="0">
                <td> <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr bgcolor="#FFFFFF"> 
                      <td width="50%"><div align="center"><strong>Layer</strong></div></td>
                      <td width="50%"><div align="center"><strong>Anzahl der Zugriffe</strong></div></td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td colspan="13"><hr></td>
                    </tr>
                    <?php
					if ( count($this->account->NumbOfAccessUserM)==0 ) { ?>
                    <tr> 
                      <td align="center" colspan="3">
                        <?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
                      </td>
                      <?php
					}
					else {
                 for ($i=0;$i<count($this->account->NumbOfAccessUserM);$i++) {
			  ?>
                    <tr bgcolor="<?php if ($i%2!=0) {
				     echo '#FFFFFF';
                   }
                   else {
                     echo '#EBEBEB';}?>"> 
                      <td><div align="center"><?php echo $this->account->NumbOfAccessUserD[$i]['lName']; ?></div></td>
                      <td><div align="center"><?php echo $this->account->NumbOfAccessUserD[$i]['NumberOfAccess']; ?></div></td>
                    </tr>
                    <?php
           }
		   }
         ?>
                  </table></td>
              </table>
            </ul></td>
			<?php }
		  if ($this->formvars['nutzung']=='stelle_nutzer') { ?>
        <tr> 
          <td colspan="2"><li>Zugriffe durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?> über 
              die Stelle: <?php echo $this->account->Bezeichnung ;?></li></td>
        <tr> 
          <td colspan="2"><ul>
              <table width="90%" border="1" cellspacing="0" cellpadding="0">
                <td> <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr bgcolor="#FFFFFF"> 
                      <td width="50%"><div align="center"><strong>Layer</strong></div></td>
                      <td width="50%"><div align="center"><strong>Anzahl der Zugriffe</strong></div></td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td colspan="13"><hr></td>
                    </tr>
                    <?php
					$accessarray=$this->account->NumbOfAccessUserStelleD;
					if ( count($this->account->NumbOfAccessUserStelleD)==0 ) { ?>
                    <tr> 
                      <td align="center" colspan="3">
                        <?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
                      </td>
                      <?php
					}
					else {
                 for ($i=0;$i<count($this->account->NumbOfAccessUserStelleD);$i++) {
			  ?>
                    <tr bgcolor="<?php if ($i%2!=0) {
				     echo '#FFFFFF';
                   }
                   else {
                     echo '#EBEBEB';}?>"> 
                      <td><div align="center"><?php echo $this->account->NumbOfAccessUserStelleD[$i]['lName']; ?></div></td>
                      <td><div align="center"><?php echo $this->account->NumbOfAccessUserStelleD[$i]['NumberOfAccess']; ?></div></td>
                    </tr>
                    <?php
           }
		   }
         ?>
                  </table></td>
              </table>
            </ul></td>
          <?php }
		 } ?>
        <tr> 
       <?php if ($this->formvars['zeitraum']=='era') { ?>
        <tr> 
          <td colspan="2"><span class="fett">Zeitraum - vom: <?php echo $this->formvars['day_e1'].'.'.$this->formvars['month_e1'].'.'.$this->formvars['year_e1']; ?> bis  <?php echo $this->formvars['day_e2'].'.'.$this->formvars['month_e2'].'.'.$this->formvars['year_e2']; ?></span><br>
		   </td>
		  <?php if ($this->formvars['nutzung']=='stelle') { ?>
        <tr> 
          <td colspan="2">&nbsp;</td>
        <tr> 
          <td colspan="2"><li>Zugriff der Stelle: <?php echo $this->account->Bezeichnung ;?></li></td>
        <tr> 
          <td colspan="2"><ul>
              <table width="90%" border="1" cellspacing="0" cellpadding="0">
                <td> <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr bgcolor="#FFFFFF"> 
                      <td width="50%"><div align="center"><strong>Layer</strong></div></td>
                      <td width="50%"><div align="center"><strong>Anzahl der Zugriffe</strong></div></td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td colspan="13"><hr></td>
                    </tr>
                    <?php
					$accessarray=$this->account->NumbOfAccessStelleE;
					if ( count($this->account->NumbOfAccessStelleE)==0 ) { ?>
                    <tr> 
                      <td align="center" colspan="3">
                        <?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
                      </td>
                      <?php
					}
					else {
                 for ($i=0;$i<count($this->account->NumbOfAccessStelleE);$i++) {
			  ?>
                    <tr bgcolor="<?php if ($i%2!=0) {
				     echo '#FFFFFF';
                   }
                   else {
                     echo '#EBEBEB';}?>"> 
                      <td><div align="center"><?php echo $this->account->NumbOfAccessStelleE[$i]['lName']; ?></div></td>
                      <td><div align="center"><?php echo $this->account->NumbOfAccessStelleE[$i]['NumberOfAccess']; ?></div></td>
                    </tr>
                    <?php
           }
		   }
         ?>
                  </table></td>
              </table>
            </ul></td>
			<?php } 
			if ($this->formvars['nutzung']=='nutzer') { ?>
        <tr> 
          <td colspan="2"><li>Zugriff durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?></li></td>
        <tr> 
          <td colspan="2"><ul>
              <table width="90%" border="1" cellspacing="0" cellpadding="0">
                <td> <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr bgcolor="#FFFFFF"> 
                      <td width="50%"><div align="center"><strong>Layer</strong></div></td>
                      <td width="50%"><div align="center"><strong>Anzahl der Zugriffe</strong></div></td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td colspan="13"><hr></td>
                    </tr>
                    <?php
					$accessarray=$this->account->NumbOfAccessUserE;
					if ( count($this->account->NumbOfAccessUserE)==0 ) { ?>
                    <tr> 
                      <td align="center" colspan="3">
                        <?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
                      </td>
                      <?php
					}
					else {
                 for ($i=0;$i<count($this->account->NumbOfAccessUserE);$i++) {
			  ?>
                    <tr bgcolor="<?php if ($i%2!=0) {
				     echo '#FFFFFF';
                   }
                   else {
                     echo '#EBEBEB';}?>"> 
                      <td><div align="center"><?php echo $this->account->NumbOfAccessUserE[$i]['lName']; ?></div></td>
                      <td><div align="center"><?php echo $this->account->NumbOfAccessUserE[$i]['NumberOfAccess']; ?></div></td>
                    </tr>
                    <?php
           }
		   }
         ?>
                  </table></td>
              </table>
            </ul></td>
			<?php }
		  if ($this->formvars['nutzung']=='stelle_nutzer') { ?>
        <tr> 
          <td colspan="2"><li>Zugriffe durch Nutzer: <?php echo $this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'] ;?> über 
              die Stelle: <?php echo $this->account->Bezeichnung ;?></li></td>
        <tr> 
          <td colspan="2"><ul>
              <table width="90%" border="1" cellspacing="0" cellpadding="0">
                <td> <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr bgcolor="#FFFFFF"> 
                      <td width="50%"><div align="center"><strong>Layer</strong></div></td>
                      <td width="50%"><div align="center"><strong>Anzahl der Zugriffe</strong></div></td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td colspan="13"><hr></td>
                    </tr>
                    <?php
					$accessarray=$this->account->NumbOfAccessUserStelleE;
					if ( count($this->account->NumbOfAccessUserStelleE)==0 ) { ?>
                    <tr> 
                      <td align="center" colspan="2">
                        <?php  echo 'Es wurden keine Einträge in der Datenbank gefunden!'; ?>
                      </td>
                      <?php
					}
					else {
                 for ($i=0;$i<count($this->account->NumbOfAccessUserStelleE);$i++) {
			  ?>
                    <tr bgcolor="<?php if ($i%2!=0) {
				     echo '#FFFFFF';
                   }
                   else {
                     echo '#EBEBEB';}?>">
                      <td><div align="center"><?php echo $this->account->NumbOfAccessUserStelleE[$i]['lName']; ?></div></td>
                      <td><div align="center"><?php echo $this->account->NumbOfAccessUserStelleEs[$i]['NumberOfAccess']; ?></div></td>
                    </tr>
                   <?php } ?>
                 </table></td>
              </table>
            </ul></td>
          <?php }
          }
		 } ?>
      </table></td>
  </tr>
  <tr> 
    <td align="center" colspan="2"><hr color="#000000" size="1"></td>
  <tr> 
    <td align="center" colspan="2"> <input name="zurueck" type="submit" value="Zurück"> </td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
</table>
<br>
<table align="center" width="400" height="300" border="1" cellpadding="1" cellspacing="1">
  <tr>
    <td align="center" bordercolor="#000000" bgcolor="#FFFFFF" ><?php include ('chart.php'); ?></td>
  </tr>
</table>
<br>
<br>
<input name="zurueck" type="submit" value="Zurück">
<br><br>
<input type="hidden" name="go" value="StatistikAuswahl">
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