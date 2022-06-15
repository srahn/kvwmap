<? if (file_exists(THIRDPARTY_PATH . 'd3js-v3/d3.v3.min.js')) { ?>
	<script type="text/javascript" src="<? echo THIRDPARTY_PATH; ?>d3js-v3/d3.v3.min.js"></script>
<? } else { ?>
	<script type="text/javascript" src="//d3js.org/d3.v3.min.js"></script>
<? } ?>

<script type="text/javascript">

	var max_depth;

	function create_force_layout(cluster_index){
		var json = cluster[cluster_index];
		var width = 1200,
				height = 700
				
		max_depth = 0;
				
		var svg = d3.select("#hierarchy_" + cluster_index).append("svg")
				.attr("width", width)
				.attr("height", height);
				
		var force = d3.layout.force()
				.gravity(.3)
				.distance(40)
				.charge(-5000)
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

			node.append("g")
					.attr("transform", 'translate(-40, 20)')
					.append("a")
							.attr("href", function(d) { return '<? echo get_url(); ?>?go=Stelleneditor&selected_stelle_id=' + d.id })
							.append("text")
									.text(function(d) { return d.name });
					
			json.links.forEach(function(link, i) {
				link.target.parents.push(link.source);
				link.source.children.push(link.target);
			});
				
			json.nodes.forEach(function(node, i) {
				if (node.parents.length == 0) {
					calculate_depth(node, 1);
				}
			});
					
			svg.attr("height", 100 + (100 * max_depth));	// Hoehe des SVGs auf die Hierarchietiefe anpassen
			
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

</script>

<style>

	.hierarchy{
		border-bottom: 1px solid #ccc;
		margin: 0 10px 0 10px;
	}

	.link {
		stroke: #ccc;
	}

	.node text {
		font: 12px SourceSansPro2;
	}
	
	.node circle{
    fill: #fff;
    stroke: steelblue;
    stroke-width: 1.5px;
	}

</style>

<br>
<h1><? echo $this->titel; ?></h1>
<br>

<? 

	$node_index = Array();
	
	foreach ($this->stellenhierarchie['clusters'] as $cluster) {
		$nodes = [];
		$links = [];
		foreach ($cluster as $stelle_id) {
			$nodes[] = '{"name": "' . $this->stellendaten['Bezeichnung'][$this->stellendaten['index'][$stelle_id]] . '", "id": "' . $stelle_id . '"}';
			$node_index[$stelle_id] = count($nodes) - 1;
		}
		foreach ($cluster as $stelle_id) {
			if (array_key_exists($stelle_id, $this->stellenhierarchie['links'])) {
				foreach ($this->stellenhierarchie['links'][$stelle_id] as $child) {
					$links[] = '{"source": ' . $node_index[$stelle_id] . ', "target": ' . $node_index[$child] . '}';
				}
			}
		}
		$nodeclusters[] = ['nodes' => $nodes, 'links' => $links];
	}

	$i = -1;
	foreach ($nodeclusters as $nodecluster) {
		$i++;
?>

<div id="hierarchy_<? echo $i; ?>" class="hierarchy">
</div>

<script type="text/javascript">

	var cluster_index = <? echo $i; ?>;
	
	var cluster = [];

	cluster[cluster_index] = {
		"nodes": [
			<? echo implode(',', $nodecluster['nodes']); ?>
		],
		"links": [
			<? echo implode(',', $nodecluster['links']); ?>
		]
	};
	
	create_force_layout(cluster_index);

</script>

<?
	}
?>


