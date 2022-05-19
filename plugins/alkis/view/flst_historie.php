<? if (file_exists(THIRDPARTY_PATH . 'd3js-v3/d3.v3.min.js')) { ?>
	<script type="text/javascript" src="<? echo THIRDPARTY_PATH; ?>d3js-v3/d3.v3.min.js"></script>
<? } else { ?>
	<script type="text/javascript" src="//d3js.org/d3.v3.min.js"></script>
<? } ?>

<script type="text/javascript">

	var max_depth;
	
	var force;

	function create_force_layout(json){
		var width = 600 + (11 * json.nodes.length),
				height = 700 + (5 * json.nodes.length)
				
		max_depth = 0;
		
		var svg = d3.select("#hierarchy_0").append("svg")
				.attr("width", width)
				.attr("height", height)
				.attr("id", 'svg_hierarchy');
				
		force = d3.layout.force()
				.gravity(1.3)
				.distance(20)
				.charge(-9000)
				.friction(0.8)
				.size([width, height]);
				
			json.nodes.forEach(function(node, i) {
				node.x = width/4 + i*200;
				node.y = 400;
				node.children = [];
				node.parents = [];
				node.depth = 0;
			});			

		force
					.nodes(json.nodes)
					.links(json.links)
					.start();

			var link = svg.selectAll(".fh-link")
					.data(json.links)
					.enter().append("line")
					//.attr("class", "fh-link")
					.attr('class', function(d) { return "fh-link f" + d.target.fkz + " f" + d.source.fkz; });

			var node = svg.selectAll(".fh-node")
					.data(json.nodes)
					.enter().append("g")
					.attr('class', function(d) { return d.fkz == '<? echo $this->formvars['flurstueckskennzeichen']; ?>' ? "fh-node requested" : "fh-node"; })
					.attr('onmouseenter', function(d) { return "highlight_link('f" + d.fkz + "');"; })
					.attr('onmouseleave', function(d) { return "dehighlight_link('f" + d.fkz + "');"; })
					.call(force.drag);

			json.links.forEach(function(link, i) {
				link.target.parents.push(link.source);
				link.source.children.push(link.target);
			});
					
			node.append("svg:circle")
					.attr("r", 6)
					.attr("class", function(d) { return d.children.length > 0 ? "hist" : ""; })

					node.append("g")
					.attr("transform", 'translate(0, 20)')
					.append("a")
						.attr("href", 'javascript:void(0)')
						.attr("onclick", function(d) { return "overlay_link('go=Flurstueck_Anzeigen&FlurstKennz=" + d.fkz + "', true)"})
						.append("text")
							.attr('text-anchor', 'middle')
							.attr('x', 0)
							.attr('dy', -31)
							.text(function(d) { return d.name1; })
							.append('svg:tspan')
								.attr('x', 0)
								.attr('dy', 31)
								.text(function(d) { return '(' + d.entstehung + ')'; })
			
			json.nodes.forEach(function(node, i) {
				if (node.parents.length == 0) {
					calculate_depth(node, 1);
				}
			});
			
			svg.attr("height", 100 + (120 * max_depth));	// Hoehe des SVGs auf die Hierarchietiefe anpassen
			
			force.on("tick", function(e) {
				var ky =  e.alpha;
				
				json.nodes.forEach(function(node, i) {
					node.y += ((node.depth * 100) - node.y) * 5 * ky;
				});			 
				
				link.attr("x1", function(d) { return d.source.x; })
					.attr("y1", function(d) { return d.source.y; })
					.attr("x2", function(d) { return d.target.x; })
					.attr("y2", function(d) { return d.target.y; });

				node.attr("transform", function(d) { return "translate(" + d.x + "," + (d.y) + ")"; });
				
			});
		
	}

	function calculate_depth(node, depth){
		if (depth > node.depth) {
			node.depth = depth;
			if (max_depth < depth) {
				max_depth = depth;
			}
		}
		if (node.children.length > 0) {
			node.children.forEach(function(child, i){
				calculate_depth(child, depth+1);
			});
		}
	}
	
	function highlight_link(fkz){
		console.log('.fh-link .' + fkz);
		var links = document.querySelectorAll('.fh-link.' + fkz);
		[].forEach.call(links, function (link){
			link.classList.add('highlighted');
		});
	}
	
	function dehighlight_link(fkz){
		console.log('.fh-link .' + fkz);
		var links = document.querySelectorAll('.fh-link.' + fkz);
		[].forEach.call(links, function (link){
			link.classList.remove('highlighted');
		});
	}

</script>

<style>

.fh-hierarchy svg{
	border: 1px solid #ccc;
	box-shadow: 0px 4px 7px 0px rgba(0,0,0,0.45);
	background: white;
}

.fh-link {
	stroke: #ddd;
	stroke-width: 2px;
}

.fh-link.highlighted{
	stroke: #6e80b5;
	stroke-width: 4px;
}

.fh-node text {
	font: 12px SourceSansPro1;
}

.fh-node.requested text{
	font: 12px SourceSansPro3;
}

.fh-node text:hover {
	font-weight: bold;
}

.fh-node circle{
	fill: #b2eaa6;
	stroke: #74c476;
	stroke-width: 3px;
	cursor: pointer;
}

.fh-node circle:hover{
	stroke-width: 5px;
}

.fh-node circle.hist {
	fill: #ddd;
	stroke: #bbb;
}

.fh-node.requested circle {
	stroke: #e00;
}

</style>

<br>
<h1>Historie des Flurstücks <? echo formatFlurstkennzALK($this->formvars['flurstueckskennzeichen']); ?></h1>
<br>

<? 

$node_index = Array();
$nodes = [];
$links = [];

foreach ($this->flst_historie as $flst) {
	$nodes[] = '{"fkz": "' . $flst['fkz'] . '", "name1": "' . $flst['name'] . '", "entstehung": "' . $flst['zde'] . '"}';
	$node_index[$flst['fkz']] = count($nodes) - 1;
}
foreach ($this->flst_historie as $flst) {
	if ($flst['nkz'] != NULL) {
		foreach (json_decode($flst['nkz']) as $child) {
			$links[] = '{"source": ' . $node_index[$flst['fkz']] . ', "target": ' . $node_index[$child] . '}';
		}
	}
}
		
?>
  
<div id="hierarchy_0" class="fh-hierarchy"></div>

<script type="text/javascript">

	var json = {
		"nodes": [
			<? echo implode(',', $nodes); ?>
		],
		"links": [
			<? echo implode(',', $links); ?>
		]
	};
	
	create_force_layout(json);

</script>

