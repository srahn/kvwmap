<?php include(LAYOUTPATH.'languages/footer_'.rolle::$language.'.php'); ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<? echo BG_DEFAULT; ?>" style="height: 100%;background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);">
	<tr>
		<td align="center"><?php echo $strPublisherName; ?>
			<a
				href="https://kvwmap.de"
				title="Informationen von der kvwmap-Homepage!"
				target="_blank"
			>kvwmap</a>
			<?php echo $this->strVersion; ?>: <? include(WWWROOT . APPLVERSION . 'version.txt'); ?>&nbsp;&nbsp;
			<?php echo $strDate; ?><?php echo date("d.m.Y",time()); ?>&nbsp;&nbsp;
			<?php echo $strUser; ?><?php echo $this->user->Namenszusatz.' '.$this->user->Vorname.' '.$this->user->Name; ?>&nbsp;&nbsp;
			<?php echo $strTask; ?><?php echo $this->Stelle->Bezeichnung; ?>
		</td><?php
		if ($this->user->funktion == 'admin' AND DEBUG_LEVEL > 0) { ?>
			<td width="1%" align="right">
				<i class="fa fa-wpforms" onclick="$('#log').toggle();" style="cursor: pointer; margin-right: 2px;"></i>
			</td><?php
		} ?>
	</tr>
</table>