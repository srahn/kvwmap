<script type="text/javascript">
	var search_requests = new Array();
	function startQuery(){
		query = document.GUI.geo_name_query.value;
		if (query.length > 3) {
			[].forEach.call(search_requests, function (search_request){
				search_request.abort();
			});
			document.getElementById('geo_name_search_result_div').innerHTML = '';
			search_requests.push(ahah("index.php", "go=geo_name_query&q="+query, new Array(document.getElementById('geo_name_search_result_div')), new Array('sethtml', $('#geo_name_search_result_div').show())));
		}
	}
</script>
<div id="search_div">
	<input id="geo_name_search_field" type="text" name="geo_name_query" autocomplete="off" onkeyup="startQuery();">
</div>
<div id="geo_name_search_result_div">
</div>