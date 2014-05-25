<?php  
  $baseUrl = Yii::app()->baseUrl; 
  $cs = Yii::app()->getClientScript();
  $cs->registerScriptFile($baseUrl.'/js/d3.min.js');
?>

<?php

$this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>$person,
    'attributes'=>array(
        array('name'=>'realname', 'label'=>'Real Name'),
        array('name'=>'company', 'label'=>'Company'),
        array('name'=>'division', 'label'=>'Division'),
        array('name'=>'position', 'label'=>'Position'),
        array('name'=>'address', 'label'=>'Address'),
        array('name'=>'baike_link', 'label'=>'Baike'),
    ),
));

?>

<style>

.link {
  stroke: #999;
  stroke-opacity: .6;
}

.node circle {
  fill: #ccc;
  stroke: #fff;
  stroke-width: 1.5px;
}

text {
  font: 1px sans-serif;
  pointer-events: none;
}

</style>


<div id="area_d3_localview">
</div>

<script>

var width = 1024,
    height = 700;

var color = d3.scale.category20();

var force = d3.layout.force()
    .charge(-120)
    .linkDistance(function(d) { 
        return d.length * 20;
     })
    .size([width, height]);

var svg_local = d3.select("#area_d3_localview").append("svg")
    .attr("viewBox", "0 0 650 800")
    .attr("preserveAspectRatio", "xMinYMin meet");
    //.attr("width", width)
    //.attr("height", height);

var graphData = 
<?php

  echo json_encode($graphData);

?>
;

  force
      .nodes(graphData.nodes)
      .links(graphData.links)
      .start();


  var link = svg_local.selectAll(".link")
      .data(graphData.links)
	    .enter().append("line")
      .attr("class", "link")
      .style("stroke-width", function(d) { return 5/d.length; })
      .style('stroke', function(d) { return d.c; });
/*
  var node = svg.selectAll(".node")
      .data(graph.nodes)
    .enter().append("circle")
      .attr("class", "node")
      .attr("r", 15)
      .style("fill", function(d) { return color(d.group); })
      .call(force.drag);*/

  var node_drag = d3.behavior.drag()
      .on("dragstart", dragstart)
      .on("drag", dragmove)
      .on("dragend", dragend);

var node = svg_local.selectAll(".node")
    .data(graphData.nodes)
    .enter().append("g")
    .attr("class", "node")
    .on("mouseover", mouseover)
    .on("mouseout", mouseout)
	  .on("click", click)
    //.call(force.drag)
    .call(node_drag);

    node.append("circle")
        .attr("r", 4)
        .style("fill", function(d) { return color(d.group); })
		.attr("personid", function(d) { return d.id; });

    node.append("text")
        .attr("x", 12)
        .attr("dy", ".35em")
        .text(function(d) { return d.name; })
        .style("font-size","1px");

  force.on("tick", function() {
    link.attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });

    node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
  });

  node.append("title")
      .text(function(d) { return d.title; });

  force.on("tick", tick);

  function mouseover() {
    d3.select(this).select("circle").transition()
        .duration(750)
        .attr("r", 16);
  }

  function mouseout() {
    d3.select(this).select("circle").transition()
        .duration(750)
        .attr("r", 4);
  }
  
  function click() {
  var personid = d3.select(this).select("circle").attr("personid");
  location.href = 'index.php?r=site/person&pid='+personid;
  
  }

  function dragstart(d, i) {
      force.stop() // stops the force auto positioning before you start dragging
  }

  function dragmove(d, i) {
      d.px += d3.event.dx;
      d.py += d3.event.dy;
      d.x += d3.event.dx;
      d.y += d3.event.dy; 
      tick(); // this is the key to make it work together with updating both px,py,x,y on d !
  }

  function dragend(d, i) {
      d.fixed = true; // of course set the node to fixed so the force doesn't include the node in its auto positioning stuff
      tick();
      force.resume();
  }

function tick() {
      link.attr("x1", function(d) { return d.source.x; })
          .attr("y1", function(d) { return d.source.y; })
          .attr("x2", function(d) { return d.target.x; })
          .attr("y2", function(d) { return d.target.y; });

      node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
    };


</script>