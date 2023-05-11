<script language="javascript" type="text/javascript">

	var scrolldown = <? echo $this->scrolldown ?: 0; ?>;
	var auto_map_resize = <? echo ($this->user->rolle->auto_map_resize ? $this->user->rolle->auto_map_resize : 0); ?>;
	var querymode = <? echo $this->user->rolle->querymode; ?>;
	var deactivatelayer = '<? echo $this->deactivatelayer; ?>';
	var deactivatequery = '<? echo $this->deactivatequery; ?>';
	var activatequery = '<? echo $this->activatequery; ?>';
	var activatelayer = '<? echo $this->activatelayer; ?>';
	var upload_only_file_metadata = <? echo intval($this->user->rolle->upload_only_file_metadata); ?>;
	var csrf_token = '<? echo $_SESSION['csrf_token']; ?>';
	var hist_timestamp = <?	echo (rolle::$hist_timestamp != ''? 'new Date("' . rolle::$hist_timestamp . '");' : "'';"); ?>
 
<?
 	if($this->user->rolle->legendtype == 1){ # alphabetisch sortierte Legende
		echo "var layernames = new Array();\n";
		$layercount = @count($this->sorted_layerset);
		for($j = 0; $j < $layercount; $j++){
			echo 'layernames['.$j.'] = \''.str_replace('"', '', str_replace("'", '', $this->sorted_layerset[$j]['alias']))."';\n";
		}
	}
?>

</script>
