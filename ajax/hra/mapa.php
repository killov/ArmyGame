<h2><?php echo $lang[66];?></h2>
<div id="map" style="width:850px;height:400px;border: solid 1px black;position:relative;height:400px;overflow: hidden;">
	<div id="move" style="width:200px;height:200px;border: solid 1px black;position:absolute;top:5px;left:5px;">
</div>

<script type="text/javascript">
	var move = 0;
	var mysX, mysY, mapX, mapY, x, y
	$("#map").mousedown(function(event){
		move = 1;
		mysX = event.pageX;
		mysY = event.pageY;
		mapX = parseInt($('#move').css("left").replace("px",""));
		mapY = parseInt($('#move').css("top").replace("px",""));
		$("body").css( 'cursor', 'move' );
	});
	$("#map").mousemove(function(event){
		if(move){
			x = mapX+(event.pageX-mysX);
			y = mapY+(event.pageY-mysY);
			console.log(x);
			document.getElementById("move").style.left = x.toString()+"px";
			document.getElementById("move").style.top = y.toString()+"px";
		}
	});
	$("#map").mouseup(function(){
		move = 0;
		$("body").css( 'cursor', 'default' );
	})

		
</script>