<script type="text/javascript">
	var search_requests = new Array();
	var keypressed;
	
	function wait(){
		var random = Math.random();
		keypressed = random;
		setTimeout(function(){if (keypressed == random) {startQuery();}}, 500);
	}
	
	function keydown_geo_name_search(event){
		if (event.keyCode == 13) {
			// bei Return zum ersten Treffer zoomen
			var links = document.querySelectorAll('#geo_name_search_result_div a');
			if (links[1]) {
				links[1].click();
			}
		}
	}
	
	function startQuery(){
		query = document.GUI.geo_name_query.value;
		if (query.length > 3) {
			[].forEach.call(search_requests, function (search_request){
				search_request.abort();
			});
			search_requests = new Array();
			document.getElementById('geo_name_search_result_div').innerHTML = '';
			search_requests.push(ahah("index.php", "go=geo_name_query&q="+query, new Array(document.getElementById('geo_name_search_result_div')), new Array('sethtml', $('#geo_name_search_result_div').show())));
		}
	}
</script>
<div id="search_div">
	<input id="geo_name_search_field" style="width: 100%" readonly onfocus="this.removeAttribute('readonly');" type="text" name="geo_name_query" autocomplete="off" onkeydown="keydown_geo_name_search(event);" onkeyup="wait();">
</div>
<div id="geo_name_search_result_div">
</div>