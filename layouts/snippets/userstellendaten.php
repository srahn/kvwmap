<?php
 # 2008-01-12 pkvvm
  include(LAYOUTPATH.'languages/userdaten_'.$this->user->rolle->language.'.php');
 ?>
<script type="text/javascript">
function Bestaetigung(link,text) {
  Check = confirm(text);
  if (Check == true)
  window.location.href = link;
}
</script>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><h2><?php echo $this->titel; ?></h2></td>
  </tr>
  <tr>
    <td>
    	<table width="100%" border="0" style="border:2px solid <?php echo BG_DEFAULT ?>"cellspacing="0" cellpadding="3">
	      <tr>
	      	<th style="border-right:1px solid <?php echo BG_DEFAULT ?>">Stelle</th>
	        <th style="border-right:1px solid <?php echo BG_DEFAULT ?>"><?php echo $this->strName;?></th>
	        <th><?php echo $strEMail;?></th>
	      </tr>
	      <?php 
	      for($s = 0; $s < count($this->stellen['ID']); $s++){ ?>
	      	<tr>
	      		<td colspan="3" bgcolor="<?php echo BG_DEFAULT ?>" style="border:1px solid <?php echo BG_DEFAULT ?>"><span class="fett"><? echo $this->stellen['Bezeichnung'][$s]; ?></span></td>
	      	</tr>
		   <? for($i=0;$i<count($this->stellen['user'][$s]['ID']);$i++) { ?>
		      <tr>
		      	<? if($i == 0){ ?><td align="center" rowspan="<? echo count($this->stellen['user'][$s]['ID']); ?>" style="border-right:1px solid <?php echo BG_DEFAULT ?>" width="200"><? echo count($this->stellen['user'][$s]['ID']).' Nutzer'; ?></td><? } ?>
		        <td style="border-right:1px solid <?php echo BG_DEFAULT ?>; border-bottom:1px solid <?php echo BG_DEFAULT ?>"><? echo $this->stellen['user'][$s]['Bezeichnung'][$i]; ?></td>
		        <td style="border-bottom:1px solid <?php echo BG_DEFAULT ?>"><? echo $this->stellen['user'][$s]['email'][$i]; ?>&nbsp;</td>
		      </tr>
		   <? } ?>
		      <tr>
		      	<td></td>
		      </tr>
		      <?
	      }
	      ?>
	      <? if($this->unassigned_users['ID']){ ?>
	      <tr>
	    		<td colspan="3" bgcolor="<?php echo BG_DEFAULT ?>" style="border:1px solid <?php echo BG_DEFAULT ?>"><span class="fett">Nicht zugeordnete Nutzer</span></td>
	    	</tr>
	   <? }
	   		for($i = 0; $i < count($this->unassigned_users['ID']); $i++){ ?>
	      <tr>
	      	<? if($i == 0){ ?><td align="center" rowspan="<? echo count($this->unassigned_users['ID']); ?>" style="border-right:1px solid <?php echo BG_DEFAULT ?>" width="200"><? echo count($this->unassigned_users['ID']).' Nutzer'; ?></td><? } ?>
	      	<td style="border-right:1px solid <?php echo BG_DEFAULT ?>; border-bottom:1px solid <?php echo BG_DEFAULT ?>"><? echo $this->unassigned_users['Bezeichnung'][$i]; ?></td
		      <td style="border-bottom:1px solid <?php echo BG_DEFAULT ?>"><? echo $this->unassigned_users['email'][$i]; ?>&nbsp;</td>
	      </tr>
	   <? } if($this->user_count){ ?>
	   		<tr>
	    		<td colspan="3" bgcolor="<?php echo BG_DEFAULT ?>" style="border:1px solid <?php echo BG_DEFAULT ?>"><span class="fett">Nutzer insgesamt</span></td>
	    	</tr>
	      <tr>
	      	<td align="center" style="border-right:1px solid <?php echo BG_DEFAULT ?>" width="200"><? echo $this->user_count.' Nutzer'; ?></td>
	      	<td style="border-right:1px solid <?php echo BG_DEFAULT ?>; border-bottom:1px solid <?php echo BG_DEFAULT ?>">&nbsp;</td
		      <td style="border-bottom:1px solid <?php echo BG_DEFAULT ?>">&nbsp;</td>
	      </tr>
	      <? } ?>
	    </table>
    </td>
  </tr>
  <tr> 
    <td align="right">&nbsp;</td>
  </tr>
</table>
