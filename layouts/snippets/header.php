 <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<? echo BG_DEFAULT; ?>" style="height: 25px;background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);">
  <tr> 
	  <td width="50%" align="right" valign="middle"><span class="fett px20"><?php echo $this->Stelle->Bezeichnung; ?></span></td>
		<td width="50%" align="left">
			<h2 style="color: #a82e2e;">&nbsp;&nbsp;&nbsp;<? if($this->user->rolle->hist_timestamp != '') echo 'Stand der ALKIS-Daten: '.$this->user->rolle->hist_timestamp; ?></h2>
		</td>	
  </tr>
</table>
