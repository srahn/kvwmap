<table width="0%" border="0" cellpadding="5" cellspacing="0">
  <tr> 
    <td bgcolor="<?php echo BG_FORM ?>"><div align="center"><h2><?php echo $this->titel; ?></h2> 
      </div></td>
  </tr>
  <tr> 
    <td bgcolor="<?php echo BG_FORM ?>"><div align="center">
	
	<?php if ($this->Fehlermeldung!='') { include(LAYOUTPATH."snippets/Fehlermeldung.php"); } ?>
	<strong><font color="#FF0000">
	<?php if ($this->Meldung!='') { echo $this->Meldung; } ?>
	</font> </strong>
	 </div>
	 
    </td>
  </tr>
  <tr>
  	<td><a href="<? echo $this->formvars['filename'] ?>"><? echo $this->formvars['filename'] ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<? echo $this->formvars['filesize'] ?>&nbsp;Bytes</td>
  </tr>
</table>
