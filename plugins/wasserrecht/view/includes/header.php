<link rel="stylesheet" type="text/css" href="<?php echo $this->actual_link ?>/../plugins/wasserrecht/styles/wasserrecht_view.css">
<script type="text/javascript" src="<?php echo $this->actual_link ?>/../plugins/wasserrecht/javascript/wasserrecht_view.js"></script>

<div class="tab">
	<div class="tablinks<?php echo $tab1_active ? ' active' : '' ?>" onclick="setNewTab('<?php echo $tab1_id ?>')"><?php echo $tab1_name ?></div>
	<div class="tablinks<?php echo $tab2_active ? ' active' : '' ?>" onclick="setNewTab('<?php echo $tab2_id ?>')"><?php echo $tab2_name ?></div>
</div>