
<script type="text/javascript">
	
	function startQuery(){
		query = document.GUI.geo_name_query.value;
		if(query.length > 5){
			ahah("index.php", "go=geo_name_query&q="+query, new Array(document.getElementById('geo_name_search_result_div')), new Array('sethtml'));
		}
	}
	
</script>

	
<div id="search_div">
	<input type="text" name="geo_name_query" onkeyup="startQuery();">
</div>
<div id="geo_name_search_result_div">
</div>