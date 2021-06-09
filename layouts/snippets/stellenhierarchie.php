<script type="text/javascript" src="//d3js.org/d3.v3.js"></script>

<? 
	$node_index = Array();
	for ($i = 0; $i < count($this->stellendaten['ID']); $i++) {
		$stelle_id = $this->stellendaten['ID'][$i];
		if ($this->stellenhierarchie[$stelle_id] != '') {
			if ($node_index[$stelle_id] === NULL) {
				$nodes[] = '{"name": "' . $this->stellendaten['Bezeichnung'][$i] . '"}';
				$node_index[$stelle_id] = count($nodes) - 1;
			}
			foreach ($this->stellenhierarchie[$stelle_id] as $child) {
				if ($node_index[$child] === NULL) {
					$nodes[] = '{"name": "' . $this->stellendaten['Bezeichnung'][$this->stellendaten['index'][$child]] . '"}';
					$node_index[$child] = count($nodes) - 1;
				}
				$links[] = '{"source": ' . $node_index[$stelle_id] . ', "target": ' . $node_index[$child] . '}';
			}
		}
	}
	
?>

<div id="hierarchy">
</div>

<script type="text/javascript">

	var json = {
		"nodes": [
			<? echo implode(',', $nodes); ?>
		],
		"links": [
			<? echo implode(',', $links); ?>
		]
	};

	var width = 1500,
			height = 800
			
	var lowest_y = 1000;

	var svg = d3.select("#hierarchy").append("svg")
			.attr("width", width)
			.attr("height", height);
			
	var force = d3.layout.force()
			.gravity(.3)
			.distance(100)
			.charge(-10000)
			.size([width, height]);
			
		json.nodes.forEach(function(node, i) {
			node.x = width/4 + i*50;
			node.y = 10*i + 600;
			node.children = [];
		});			

	force
				.nodes(json.nodes)
				.links(json.links)
				.start();

		var link = svg.selectAll(".link")
				.data(json.links)
			.enter().append("line")
				.attr("class", "link");

		var node = svg.selectAll(".node")
				.data(json.nodes)
			.enter().append("g")
				.attr("class", "node")
				.call(force.drag);

		node.append("svg:circle")
				.attr("r", 6);

		node.append("text")
				.attr("dx", -40)
				.attr("dy", 20)
				.text(function(d) { return d.name });
				
		json.links.forEach(function(link, i) {
			link.source.children.push(link.target);
		});
			
		json.nodes.forEach(function(node, i) {
			if (!node.depth) {
				calculate_depth(node);
			}
		});
		
		function calculate_depth(node){
			node.depth = 1;
			if (node.children.length > 0) {
				node.children.forEach(function(child, i){
					var child_depth = calculate_depth(child);
					if (child_depth > node.depth) {
						node.depth = child_depth;
					}
				});
				node.depth++;
			}
			return node.depth;
		}

		force.on("tick", function(e) {
				var ky =  e.alpha;
			// json.links.forEach(function(d, i) {
				// d.source.y -= k;
				// d.target.y += k;
			// });
			  json.nodes.forEach(function(node, i) {
				  //node.y += (node.depth * 100 - node.y) * 5 * ky;
					node.y -= ((node.depth * 100) + node.y) * 5 * ky;
					if (node.y < lowest_y) {
						lowest_y = node.y;
					}
			  });
			 
			// json.links.forEach(function(d, i) {
				// d.target.y += (d.target.depth * 100 - d.target.y) * 5 * ky;
			// });
			// json.nodes.forEach(function(d, i) {
					// if(d.children) {
							// if(i>0) {
									// var childrenSumX = 0;
									// d.children.forEach(function(d, i) {
											// childrenSumX += d.x;
									// });
									// var childrenCount = d.children.length;
									// d.x += ((childrenSumX/childrenCount) - d.x) * 5 * ky;
							// }
							// else {
									// d.x += (width/2 - d.x) * 5 * ky;
							// };
					// };
			// });			 
			 
			link.attr("x1", function(d) { return d.source.x; })
					.attr("y1", function(d) { return d.source.y-lowest_y-200; })
					.attr("x2", function(d) { return d.target.x; })
					.attr("y2", function(d) { return d.target.y-lowest_y-200; });

			node.attr("transform", function(d) { return "translate(" + d.x + "," + (d.y-lowest_y-200) + ")"; });
		});

</script>

<style>

	.link {
		stroke: #ccc;
	}

	.node text {
		pointer-events: none;
		font: 10px sans-serif;
	}
	
	.node circle{
    fill: #fff;
    stroke: steelblue;
    stroke-width: 1.5px;
	}

</style>
