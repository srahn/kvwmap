 <h2>               <?php 
                                echo $this->titel;
                                ?></h2><br>

  <?php 
                                echo $this->Fehlermeldung;
                                ?>
  <br>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php 
  echo $this->ALK->alk_protokoll_einlesen; ?><p><?php
  echo $this->ALB->Protokoll_Aktualisieren;
  ?><p><input type="hidden" name="go" value="default">
<input type="submit" name="submit" value="Weiter">
    </td>
  </tr>
</table>


