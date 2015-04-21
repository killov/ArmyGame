<!doctype html>
<html>
	<head>
		<title>Armygame</title>
		<link href="jquery-ui.css" rel="stylesheet">
		<link rel="stylesheet" href="styleg.css" type="text/css">
		<script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
		<script src="js/jquery-ui.min.js"></script>
		<script src="js/jquery.mousewheel.min.js"></script>
		
		<script type="text/javascript" src="js/script.js"></script>
		<meta charset="UTF-8">
		  <script>
		$(function() {
			$( document ).tooltip({
				track: true,
				show: { easing: "easeInExpo", duration: 1500 }
			});
			$("#drevoc").tooltip({
				track: true,
				content: function(){
					return $("#drevo_p").text();
				}
			});
			$("#kamenc").tooltip({
				track: true,
				content: function(){
					return $("#kamen_p").text();
				}
			});
			$("#zelezoc").tooltip({
				track: true,
				content: function(){
					return $("#zelezo_p").text();
				}
			});
			$("#obilic").tooltip({
				track: true,
				content: function(){
					return $("#obili_p").text();
				}
			});

		});
  </script>
	</head>
	<body>
	<div id="header">
		
		<div id="h">
			
			<div id="hornimenu">
				<ul>
					<li class="mesto">
						<a href="#" onMouseDown="page_load('mesto')" onMouseUp="page_draw()" title="<?php echo $lang[25];?>"> </a>
					</li>
					<li class="mapa">
						<a href="#" onClick="mapa()" title="<?php echo $lang[66];?>"> </a>
					</li>
					<li class="stat">
						<a href="#" onMouseDown="page_load('statistika')" onMouseUp="page_draw()" title="<?php echo $lang[58];?>"></a>
					</li>
					<li class="zpravy<?php if($user["zprava"]){echo " zpravyn";}?>">
						<a href="#" onMouseDown="page_load('zpravy')" onMouseUp="page_draw()" title="<?php echo $lang[41];?>"></a>
					</li>
					<li class="profil">
						<a href="#" onMouseDown="page_load('profil')" onMouseUp="page_draw()" title="<?php echo $lang[33];?>"></a>
					</li>					
				</ul>
			</div>
			
			<div id="suroviny">
				<div class="surovina" id="drevoc" title="">
					<img src="img/drevo.png">
					<div>
						<span id="drevo"><?php echo surovina($mesto["drevo"],$mesto["drevo_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());?></span>
					</div>
					<div class="hidden">
						<span id="drevo_p"><?php echo $mesto["drevo_produkce"];?></span>
					</div>
				</div>
				<div class="surovina" id="kamenc" title="">
					<img src="img/kamen.png">
					<div>
						<span id="kamen"><?php echo surovina($mesto["kamen"],$mesto["kamen_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());?></span>
					</div>
					<div class="hidden">
						<span id="kamen_p"><?php echo $mesto["kamen_produkce"];?></span>
					</div>
				</div>
				<div class="surovina" id="zelezoc" title="">
					<img src="img/zelezo.png">
					<div>
						<span id="zelezo"><?php echo surovina($mesto["zelezo"],$mesto["zelezo_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());?></span>
						</div>
					<div class="hidden">
						<span id="zelezo_p"><?php echo $mesto["zelezo_produkce"];?></span>
					</div>
				</div>
				<div class="surovina" id="obilic" title="">
					<img src="img/obili.png">
					<div>
						<span id="obili"><?php echo surovina($mesto["obili"],$mesto["obili_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());?></span>
					</div>
					<div class="hidden">
						<span id="obili_p"><?php echo $mesto["obili_produkce"];?></span>
					</div>
				</div>
				<div class="surovina">
					<img src="img/sklad.png">
					<div>
						<span id="sklad"><?php echo $mesto["sklad"];?></span>
					</div>
				</div>
			</div>
			<script type="text/javascript">
				var drevo = <?php echo surovina($mesto["drevo"],$mesto["drevo_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());?>;
				var drevo_p = <?php echo $mesto["drevo_produkce"];?>;
				var kamen = <?php echo surovina($mesto["kamen"],$mesto["kamen_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());?>;
				var kamen_p = <?php echo $mesto["kamen_produkce"];?>;
				var zelezo = <?php echo surovina($mesto["zelezo"],$mesto["zelezo_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());?>;
				var zelezo_p = <?php echo $mesto["zelezo_produkce"];?>;
				var obili = <?php echo surovina($mesto["obili"],$mesto["obili_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());?>;
				var obili_p = <?php echo $mesto["obili_produkce"];?>;
				var sklad = <?php echo $mesto["sklad"];?>;

				setInterval("produkce()",1000);
				setInterval("data_load()",20000);
			</script>
		</div>
		
		<div id="p">
			<ul>
				<li class="logout">
					<a href="?odhlas"> </a>
				</li>
			</ul>
		</div>
	</div>
	<div id="pravej">
		<div>
		
		</div>
	</div>
	<div id="hlaska">
	</div>
	<div id="pozx">
		<div id="pozxmove">
		
		</div>
	</div>
	<div id="pozy">
		<div id="pozymove">

		</div>
	</div>
	<div id="back">
		<div id="move" style="position:absolute;top:20px;left:20px;width:1000px;height:1000px;">

		</div>
	</div>

    <div class="map_options">
        <div class="map_zoom"></div>
        <div class="map_zoom_pop1">0.4x</div>
        <div class="map_zoom_pop2">0.6x</div>
        <div class="map_zoom_pop3">1x</div>
    </div>
<script type="text/javascript">
	var mesto = <?php echo $mesto["id"];?>;
	var mesto_x = <?php echo $mesto["x"];?>;
	var mesto_y = <?php echo $mesto["y"];?>;
	$('#back').mousewheel(function(e){
		var sirka = parseInt($("#back").css("width").replace("px",""));
		var vyska = parseInt($("#back").css("height").replace("px",""))
        if(e.deltaY > 0) {
			if(velikost < 1000){
				var ve = velikost;
				velikost = velikost+50;
				zoom(ve,velikost,(sirka/2)-e.pageX,(vyska/2)-e.pageY);

        		$('.map_zoom').slider('value', velikost);
			}
        }
        else{
			if(velikost > 400){
				var ve = velikost;
				velikost = velikost-50;
				zoom(ve,velikost,(sirka/2)-e.pageX,(vyska/2)-e.pageY);
        		$('.map_zoom').slider('value', velikost);
			}
		}
    });
	$('.map_zoom').slider({
        min: 400,
        max: 1000,
        step: 50,
        value: velikost,
        slide: function(event, ui){
            var orig = velikost;
            velikost = ui.value;
            zoom(orig, ui.value);
        },
        change: function(event, ui){
			var orig = velikost;
            velikost = ui.value;
            zoom(orig, velikost,0,0);
        }
    });
    $('.map_zoom_pop1').click(function(){
        var orig= velikost;
        velikost = 400;
        zoom(orig,400,0,0);
        $('.map_zoom').slider('value', 400);

    });
    $('.map_zoom_pop2').click(function(){
        var orig= velikost;
        velikost = 600;
        zoom(orig,600,0,0);
        $('.map_zoom').slider('value', 600);

    });
    $('.map_zoom_pop3').click(function(){
        var orig= velikost;
        velikost = 1000;
        zoom(orig,1000,0,0);
        $('.map_zoom').slider('value', 1000);

    });
	$("#back").mousedown(function(event){
		tahni(event,1);
	});
	$(document).mousemove(function(event){
		pohyb(event);
	});
	$(document).mouseup(function(){
		pust();
	});
	$("#back").on('touchstart',function(e){
		e.preventDefault();
		var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
		tahni(touch,0);
	});
	$("#back").on('touchmove',function(e){
		e.preventDefault();
		var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
		pohyb(touch);
	});
	$("#back").on('touchend',function(){
		pust();
	});
	mapa_pozices(<?php echo $mesto["x"].",".$mesto["y"];?>,0);
</script>
		<div id="celek">
			<div id="obsah">
				<div id="obsah_h">
					<?php include "ajax/hra/mesto.php";?>
				</div>
			</div>
		</div>
	</body>