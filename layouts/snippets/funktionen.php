<script type="text/javascript">
function Bestaetigung(link,text) {
  Check = confirm(text);
  if (Check == true)
  window.location.href = link;
}
</script>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><strong><font size="+1">Funktionen</font></strong></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <th><a href="index.php?go=Funktionen_Anzeigen&order=id">ID</a></th>
        <th><a href="index.php?go=Funktionen_Anzeigen&order=bezeichnung">Bezeichnung</a></th>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php 
      for ($i=0;$i<count($this->funktionen);$i++) { ?>
      <tr>
        <td><?php echo $this->funktionen[$i]['id']; ?></td>
        <td><?php echo $this->funktionen[$i]['bezeichnung']; ?></td>
        <td><a href="index.php?go=Funktionen_Formular&selected_function_id=<?php echo $this->funktionen[$i]['id']; ?>">Bearbeiten</a></td>
        <td>&nbsp;&nbsp;<a href="javascript:Bestaetigung('index.php?go=Funktion_Löschen&selected_function_id=<?php echo $this->funktionen[$i]['id']; ?>&order=<? echo $this->formvars['order']; ?>','Wollen Sie diese Funktion wirklich löschen?')">Löschen</a></td>
      </tr>
      <?php  
      }
      ?>
    </table></td>
  </tr>
  <tr> 
    <td align="right">&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="go" value="Funktionen">
<input type="hidden" name="order" value="<? echo $this->formvars['order'] ?>">
