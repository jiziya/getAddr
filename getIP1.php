<?php
	require_once('IpLocation.class.php');		// 导入IpLocation类
	
	$ipclass = new IpLocation('qqwry.dat');		// 实例化类 参数表示IP地址库文件
	$ip = $ipclass -> get_client_ip();			// 获取访问者IP
	$area = $ipclass -> getlocation($ip);		// 获取IP地址所在的位置
	
	foreach($area as $key => $val) {
		$area[$key] = iconv("gb2312", "utf-8", $val);	// 转换结果的编码
	}
	$addr = $area['area'];
	$real_addr = $area['country'].' ' .$area['area'];
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Get your Address</title>
	<style>
		* { padding: 0; margin: 0;}
		html { height: 100%; margin-bottom: -25px;}
		body { height: 100%; position: relative;}
		.tips { position: absolute; top: 0; left: 0; width: 100%; background: #abcdef; vertical-align: text-top; z-index: 999;}
		.tips span { padding-left: 10px;}
		.tips input { width: 400px; height: 25px; line-height: 25px;}
		.tips button { padding: 3px 8px;}
		#map { width: 100%; height: 100%;}
		#r-result { position: absolute; top: 82px; max-height: 500px; width: 270px; overflow: scroll; background: #fff;}
	</style>
</head>
<body onload="init()">
	<div class="tips">
		<span><?php echo $area['country'] ?></span>
		<input type="text" value="" id="addr" />
		<button onclick="init()">搜索</button>
		<span>您的IP所在地：<?php echo $real_addr ?></span>
	</div>
	<div id="map"></div>
	<div id="r-result"></div>
	<div id="container"></div>
</body>
<script src="http://api.map.baidu.com/api?v=2.0&ak=026e2123b614b31bbd5e284f8457c2b5"></script>
<script>
	function init() {
		var addr_f = document.getElementById('addr').value;		// 初始化搜索关键字
		var addr_p = '<?php echo $addr ?>';
		if(addr_f == '') {
			var addr = addr_p != '' ? addr_p : '北京市';
		}else{
			var addr = addr_f;
		}
		var map = new BMap.Map("map");					// 创建地图实例
		
		// 将地址解析结果显示在地图上，并调整地图视野
		var myGeo = new BMap.Geocoder();
		myGeo.getPoint(addr, function(point){
			//map.centerAndZoom(point, 16);	// 设置搜索后的地图中心点
			map.enableScrollWheelZoom();	// 开启鼠标滚轮缩放地图
		}, "北京市");
		var local = new BMap.LocalSearch(map, {
			renderOptions: {map: map, panel: "r-result"}	// 显示搜索结果菜单
		});
		local.search(addr);
	}
</script>
</html>