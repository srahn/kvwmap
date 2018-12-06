 <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<? echo BG_DEFAULT; ?>" style="height: 25px;background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);">
  <tr> 
    <td width="50%" align="right" valign="middle"><span class="fett px20"><?php echo $this->Stelle->Bezeichnung; ?></span></td>
    <td width="50%" align="middle">
      Jahr:
      <select name="jahr"><?php
        for ($i = 15; $i < 41; $i++) { ?>
          <option value="<?php echo $i; ?>"<?php if ($this->formvars['jahr'] == $i) echo ' selected'; ?>>20<?php echo $i; ?></option><?php
        } ?>
      </select>
      Geschlecht:
      <select name="geschlecht"><?php
        $select_data = array(
          'm' => 'männlich',
          'w' => 'weiblich',
          'g' => 'gesamt'
        );
        foreach ($select_data AS $key => $output) {
          ?><option value="<?php echo $key; ?>"<?php if ($this->formvars['geschlecht'] == $key) echo ' selected'; ?>><?php echo $output; ?></option><?php
        } ?>
      </select>
    </td>
  </tr>
</table>
