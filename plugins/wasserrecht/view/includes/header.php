<link rel="stylesheet" type="text/css" href="<?php echo $this->actual_link ?>/../plugins/wasserrecht/styles/wasserrecht_view.css">
<script type="text/javascript" src="<?php echo $this->actual_link ?>/../plugins/wasserrecht/javascript/wasserrecht_view.js"></script>

<div class="tab">
	<div class="tablinks<?php echo $tab1_active ? ' active' : '' ?>" <?php echo $tab1_visible ? '' : ' style="display: none;"' ?> onclick="setNewTab('<?php echo $tab1_id ?>'<?php echo !empty($tab1_extra_parameter_key) && !empty($tab1_extra_parameter_value) ? ",{'" . $tab1_extra_parameter_key . "':'" . $tab1_extra_parameter_value . "'}" : "" ?>)"><?php echo $tab1_name ?></div>
	<?php 
	if(!empty($tab2_id) && !empty($tab2_name))
	{
	?>
		<div class="tablinks<?php echo $tab2_active ? ' active' : '' ?>" <?php echo $tab2_visible ? '' : ' style="display: none;"' ?> onclick="setNewTab('<?php echo $tab2_id ?>'<?php echo !empty($tab2_extra_parameter_key) && !empty($tab2_extra_parameter_value) ? ",{'" . $tab2_extra_parameter_key . "':'" . $tab2_extra_parameter_value . "'}" : "" ?>)"><?php echo $tab2_name ?></div>
	<?php 
	}
	?>
</div>