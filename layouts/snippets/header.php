 <?php
	global $supportedLanguages;
	include(LAYOUTPATH . 'languages/header_' . $this->user->rolle->language . '.php'); 
?>

<style>
	#search_div {
		display: none;
	}
</style>

<div style="
	width: 100%;
	height: 36px;
	background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);
	display: flex;
  justify-content: space-between;
">

	<div style="padding: 6px; float: left; width: 54%; text-align: left;">
		<span class="fett px20"><?php
			echo $this->Stelle->getName(); ?>
		</span>
	</div>

	<? include(LAYOUTPATH . 'snippets/headermenues.php'); ?>

</div>

<div id="sperr_div" class="sperr-div" onclick="
	$('#user_options').toggle();
	$('#sperr_div').toggle()
">
</div>