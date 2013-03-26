<script language="JavaScript" type="text/javascript">
<!--

-->
</script>

<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr> 
    <td align="right">&nbsp;</td>
  </tr>
  <tr align="center"> 
    <td><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
  <tr> 
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td><table width="100%" style="border-collapse:collapse;" border="1" cellspacing="1" cellpadding="4">
      <tr>
      	<? if(!$this->formvars['oid']){ ?><td class="bold">Jagdbezirk</td><? } ?>
      	<td class="bold">Eigentümer</td>
        <td class="bold">Anteil nach ALB</td>
        <td class="bold">Anteil nach ALK</td>
        <td class="bold">ALB-Fläche</td>
      </tr>
      <?php 
      for ($i = 0; $i < count($this->eigentuemer); $i++) { ?>
      <tr bgcolor="#E6E6F0">
      	<? if(!$this->formvars['oid']){ ?><td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><? echo $this->flurstuecke[$i]['name']; ?></td><? } ?>        
        <td width="49%"><? echo $this->eigentuemer[$i]['eigentuemer']; ?></td>
        <td width="17%"><? echo $this->eigentuemer[$i]['anteil_alb']; ?> %</td>
        <td width="17%"><? echo $this->eigentuemer[$i]['anteil_alk']; ?> %</td>
        <td width="17%"><? echo $this->eigentuemer[$i]['albflaeche']; ?></td>
      </tr>
      <?php  
      }
      ?>
    </table></td>
  </tr>
  <? if($this->formvars['oid']){ ?>
  <tr>
  	<td align="center"><a href="javascript:document.GUI.go.value = 'jagdbezirk_show_data';javascript:document.GUI.submit()">zurück zum Jagdbezirk</a></td>
  </tr>
  <? }else{ ?>
  <tr>
    <td align="center"><a href="javascript:document.GUI.go.value = 'jagdbezirke_auswaehlen_Suchen';javascript:document.GUI.submit()">zur&uuml;ck zur Trefferliste</a></td>
  </tr>
  <? } ?>
  <tr>
  	<td align="center"><a href="javascript:hideMenue();javascript:print();">Drucken</a></td>
  </tr>
  <tr> 
    <td align="right">&nbsp;</td>
  </tr>
</table>

<input name="go" type="hidden" value="jagdbezirk_show_data">
<input name="oid" type="hidden" value="<? echo $this->formvars['oid']; ?>">
<input name="name" type="hidden" value="<? echo $this->formvars['name']; ?>">
<input name="search_nummer" type="hidden" value="<?php echo $this->formvars['search_nummer']; ?>">
<input name="search_name" type="hidden" value="<?php echo $this->formvars['search_name']; ?>">
<input name="search_art" type="hidden" value="<?php echo $this->formvars['search_art']; ?>">
<input name="FlurstKennz" type="hidden" value="">
<input name="jagdkataster" type="hidden" value="true">

