<?php  
  $baseUrl = Yii::app()->baseUrl; 
  $cs = Yii::app()->getClientScript();
  $cs->registerScriptFile('http://api.map.baidu.com/api?v=2.0&ak=ttrp3RWYkVE7qlSEwaakjXiQ');
  $cs->registerScriptFile('http://api.map.baidu.com/library/Heatmap/2.0/src/Heatmap_min.js');
  
?>

<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
body, html,#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;}
#l-map{height:100%;width:78%;float:left;border-right:2px solid #bcbcbc;}
#r-result{height:100%;width:20%;float:left;}
</style>

<div id="map" style="height:800px"></div>

<script type="text/javascript">

function createMarker(data, point)
{
    var marker = new BMap.Marker(data, point);        // 创建标注  
      marker.addEventListener("mouseover", function(e){ 
          this.setTitle(data.info);
      });
    map1.addOverlay(marker);   

    var label = new BMap.Label(data.name,{offset:new BMap.Size(20,-10)});
      label.setStyle({
             color : "black",
             fontSize : "5px",
             height : "12px",
             lineHeight : "12px",
             fontFamily:"微软雅黑",
             "borderStyle":"0",
             "backStyle":"0",
             "borderColor": "#ffffff",
             "color": "#333"
           });
    marker.setLabel(label);
}

// 百度地图API功能
var map1 = new BMap.Map("map");            // 创建Map实例
var point = new BMap.Point(116.404, 39.915);    // 创建点坐标
map1.centerAndZoom(point,5);                     // 初始化地图,设置中心点坐标和地图级别。
map1.enableScrollWheelZoom();                            //启用滚轮放大缩小

var friendLocData = 
<?php
  echo json_encode($friendLocData);
?>
;


for(var index in friendLocData)
{
  if(friendLocData[index].lng!=null && friendLocData[index].lat!=null)
  {
    var point = new BMap.Point(friendLocData[index].lng, friendLocData[index].lat);
                  // 将标注添加到地图中 
    createMarker(friendLocData[index], point);

  }
}


var points = <?php echo json_encode($hotAreaData);?>;
heatmapOverlay = new BMapLib.HeatmapOverlay({"radius":20});
map1.addOverlay(heatmapOverlay);
heatmapOverlay.setDataSet({data:points,max:10});

</script>