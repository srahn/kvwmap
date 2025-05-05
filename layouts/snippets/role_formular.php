<?
	include(LAYOUTPATH . 'languages/role_formular_' . rolle::$language . '.php');
?>
<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<div class="center-outerdiv">
	<div class="input-form" style="min-width: 620px">
		<h2><?php echo $strTitle; ?></h2>
		<em><span class="px13"><? echo $this->strAsteriskRequired; ?></span></em><br><?php
		echo $this->role->as_form_html(); ?>
		<div class="clear"></div>
		<div style="text-align: -webkit-center">
			<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
				<tr>
					<td align="center">
						<input type="hidden" name="go_plus" id="go_plus" value="">
						<input
							value="<? echo $this->strButtonBack; ?>"
							title="<? echo $strShowRoleList; ?>"
							type="button"
							name="go"
							onclick="document.location.href='index.php?go=role_list&csrf_token=<? echo $_SESSION['csrf_token']; ?>#rolle_<?php echo $this->role->get('id'); ?>'"
						>&nbsp;
						<input value="<? echo $this->strChange; ?>" title="<? echo $this->strChangeTitle; ?>" type="button" name="dummy" onclick="submitWithValue('GUI','go_plus','update')">&nbsp;
						<input value="<? echo $this->strReset; ?>" title="<? echo $this->strResetTitle; ?>." type="reset" name="reset1">&nbsp;
						<input type="hidden" name="user_id" value="<?php echo  $this->role->get('user_id'); ?>">
						<input type="hidden" name="stelle_id" value="<?php echo  $this->role->get('stelle_id'); ?>">
				  </td>
			  </tr>
			</table>
			<input type="hidden" name="go" value="role">
		</div>
	</div>
</div>
<script>
	function getrolleLink(elm) {
		if (elm.name == 'onclick') {
			return '?' + elm.value.match(/'([^']+)'/)[1];
		}
		else {
			return elm.value . '&csrf_token=' . $_SESSION['csrf_token'];
		}
	}
	function createrolleLink(elm) {
		$('input[name=' + elm.name + ']').attr('style', 'width: 374px').after($('<a id="link_' + elm.name + '" style="\
			float: right;\
			margin-top: 2px;\
			font-size: 19px;\
			margin-right: 2px;\
		" href="' + getrolleLink(elm) + '" target="_blank" title="Link in neuem Tab anzeigen"><i style="color: firebrick" class="fa fa-external-link" aria-hidden="true"></i></a>'));
	}
	function updaterolleLink(elm) {
		var link;
		if (elm.value != '' && !elm.value.includes('javascript:void(0)')) {
			if ($('#link_' + elm.name).length == 0) {
				createrolleLink(elm);
			}
			else {
				$('#link_' + elm.name).attr('href', getrolleLink(elm));
			}
		}
		else {
			if ($('#link_' + elm.name).length > 0) {
				removerolleLink(elm);
			}
		}
	}
	function removerolleLink(elm) {
		$('input[name=' + elm.name + ']').attr('style', 'width: 400px');
		$('#link_' + elm.name).remove();
	}

	$('input[name=links], input[name=onclick]').on(
		'change',
		function(evt) {
			updaterolleLink(evt.target);
		}
	);

	updaterolleLink($('input[name=links]')[0]);
	updaterolleLink($('input[name=onclick]')[0]);
</script>