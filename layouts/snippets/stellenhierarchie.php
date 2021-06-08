<script type="text/javascript" src="//d3js.org/d3.v3.js"></script>

<? print_r($this->stellendaten);
	$node_index = Array();
	for ($i = 0; $i < count($this->stellendaten['ID']); $i++) {
		//$nodes[] = '{"name": "' . $this->stellendaten['Bezeichnung'][$i] . '"}';
		//$node_index[$this->stellendaten['ID'][$i]] = $i;
		if ($this->stellendaten['parent_id'][$i] != '') {
			$current_index = count($node_index);
			$nodes[] = '{"name": "' . $this->stellendaten['Bezeichnung'][$i] . '"}';
			$node_index[$this->stellendaten['ID'][$i]] = $current_index;
			if ($node_index[$this->stellendaten['parent_id'][$i]] === NULL) {
				$nodes[] = '{"name": "' . $this->stellendaten['Bezeichnung_parent'][$i] . '"}';
				$node_index[$this->stellendaten['parent_id'][$i]] = $current_index + 1;
			}
			$links[] = '{"source": ' . $node_index[$this->stellendaten['parent_id'][$i]] . ', "target": ' . $current_index . ', "type": "parent"}';
		}
	}
	
?>

<div id="hierarchy">
http://jsfiddle.net/VividD/5Yv24/
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

	var width = 960,
			height = 800

	var svg = d3.select("#hierarchy").append("svg")
			.attr("width", width)
			.attr("height", height);
			
	json.nodes.forEach(function(d, i) {
    d.x = width/2 + i;
    d.y = 100*i + 100;
	});

	var force = d3.layout.force()
			.gravity(.2)
			.distance(100)
			.charge(-750)
			.size([width, height]);

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

		node.append("image")
				.attr("xlink:href", "https://github.com/favicon.ico")
				.attr("x", -8)
				.attr("y", -8)
				.attr("width", 16)
				.attr("height", 16);

		node.append("text")
				.attr("dx", 12)
				.attr("dy", ".35em")
				.text(function(d) { return d.name });

		force.on("tick", function(e) {
				var k = 6 * e.alpha;
			json.links.forEach(function(d, i) {
				d.source.y -= k;
				d.target.y += k;
			});
			link.attr("x1", function(d) { return d.source.x; })
					.attr("y1", function(d) { return d.source.y; })
					.attr("x2", function(d) { return d.target.x; })
					.attr("y2", function(d) { return d.target.y; });

			node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
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

</style>
