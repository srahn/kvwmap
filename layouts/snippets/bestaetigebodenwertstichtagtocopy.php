 <h2>               <?php 

        echo $this->titel;

        ?></h2>



  <?php 

        echo $this->Fehlermeldung;

        ?>

  <br>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="<?php echo BG_FORM ?>">

  <tr>

    <td align="center"><p>

        <br>
        <br>
        Wollen Sie wirklich die Bodenrichtwertzonen vom <?php echo $this->formvars['oldStichtag']; ?> nach <?php echo $this->formvars['newStichtag']; ?> kopieren?<br>
        <br>
        <input type="submit" name="bestaetigung" value="Ja">
&nbsp;
<input type="submit" name="bestaetigung" value="Nein">
<br>
<br><input type="hidden" name="go" value="BodenrichtwertzonenKopieren_Senden">
<input type="hidden" name="newbwlayer" value="<?php echo $this->formvars['newbwlayer']; ?>">
<input type="hidden" name="group_id" value="<?php echo $this->formvars['group_id']; ?>">
<input type="hidden" name="oldStichtag" value="<?php echo $this->formvars['oldStichtag']; ?>">
<input type="hidden" name="newStichtag" value="<?php echo $this->formvars['newStichtag']; ?>">

    </td>

  </tr>
</table>





