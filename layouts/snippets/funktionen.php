<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><h2>Funktionen</h2></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <th><a href="index.php?go=Funktionen_Anzeigen&order=id&csrf_token=<? echo $_SESSION['csrf_token']; ?>">ID</a></th>
        <th><a href="index.php?go=Funktionen_Anzeigen&order=bezeichnung&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Bezeichnung</a></th>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php 
      for ($i=0;$i<count($this->funktionen);$i++) { ?>
      <tr>
        <td><?php echo $this->funktionen[$i]['id']; ?></td>
        <td><?php echo $this->funktionen[$i]['bezeichnung']; ?></td>
        <td><a href="index.php?go=Funktionen_Formular&selected_function_id=<?php echo $this->funktionen[$i]['id']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Bearbeiten</a></td>
        <td>&nbsp;&nbsp;<a href="javascript:Bestaetigung('index.php?go=Funktion_Löschen&selected_function_id=<?php echo $this->funktionen[$i]['id']; ?>&order=<? echo $this->formvars['order']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>','Wollen Sie diese Funktion wirklich löschen?')">Löschen</a></td>
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
