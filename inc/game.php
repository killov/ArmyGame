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

			<canvas id="myCanvas" width="200" height="100" style="border:1px solid #d3d3d3;position: absolute;">
            Your browser does not support the HTML5 canvas tag.</canvas>

            <script>
            var c = document.getElementById("myCanvas");
            var ctx = c.getContext("2d");
            ctx.moveTo(0,0);
            ctx.lineTo(200,100);
            ctx.stroke();
            </script>
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
	var move = 0;
	var mysX, mysY, mapX, mapY, x, y
	var nacteno = new Array();
	var nactenovi = new Array();
	var nactenoi = 0;
	var nactenox = new Array();
	var nactenoy = new Array();
	var poleinfo = new Array();
	var nacitani;
	var klik = 1;
	var kliktimer;
	var velikost = 1000;
	var dotykX, dotykY;
	
	 $('#back').mousewheel(function(e){
        if(e.deltaY > 0) {
			if(velikost < 1000){
				var ve = velikost;
				velikost = velikost+100;
				zoom(ve,velikost);
                $('.map_zoom').slider('value', velikost);
			}
        }
        else{
			if(velikost > 400){
				var ve = velikost;
				velikost = velikost-100;
				zoom(ve,velikost);
                $('.map_zoom').slider('value', velikost);
			}
		}
    });
	
	
	function zoom(z,na){
		var sirka = parseInt($("#back").css("width").replace("px",""));
		var vyska = parseInt($("#back").css("height").replace("px",""));
		mapX = mapX*(na/z)+(sirka/2)*(1-na/z);
		mapY = mapY*(na/z)+(vyska/2)*(1-na/z);
		$("#move").stop().animate({
			width: velikost,
			height: velikost,
			left: mapX,
			top: mapY
			}, 500);
		$("#pozxmove").stop().animate({
			width: velikost,
			left: mapX
			}, 1);
		$("#pozymove").stop().animate({
			height: velikost,
			top: mapY
		}, 1);
		mapa_load();
	}

	$('.map_zoom').slider({
        min: 400,
        max: 1000,
        step: 25,
        value: velikost,
        slide: function(event, ui){
            var orig = velikost;
            velikost = ui.value;
            zoom(orig, ui.value);
        },
        change: function(event, ui){
            velikost = ui.value;
            zoom(velikost, velikost);
        }
    });
    $('.map_zoom_pop1').click(function(){
        var orig= velikost;
        velikost = 400;
        zoom(orig,400);
        $('.map_zoom').slider('value', 400);

    });

    $('.map_zoom_pop2').click(function(){
        var orig= velikost;
        velikost = 600;
        zoom(orig,600);
        $('.map_zoom').slider('value', 600);

    });
    
    $('.map_zoom_pop3').click(function(){
        var orig= velikost;
        velikost = 1000;
        zoom(orig,1000);
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
	
	
	function tahni(event,zo){
		if(document.selection && document.selection.empty){
			document.selection.empty();
		}
		else if(window.getSelection)
		{
			var sel = window.getSelection();
			if(sel && sel.removeAllRanges) sel.removeAllRanges();
		}
		move = 1;
		kliktimer = setTimeout(function(){klik = 0;},500);
		mysX = event.pageX;
		mysY = event.pageY;
		mapX = parseInt($('#move').css("left").replace("px",""));
		mapY = parseInt($('#move').css("top").replace("px",""));
		$("body").css( {'cursor': 'move','user-select': 'none' });
		nacitani = setInterval("mapa_load()",500);
		showmap();
	}
	
	function pohyb(event){
		if(move){
			if(event.pageX-mysX > 5 || event.pageY-mysY > 5){
				klik = 0;
			}
			x = mapX+(event.pageX-mysX);
			y = mapY+(event.pageY-mysY);
			document.getElementById("move").style.left = x.toString()+"px";
			document.getElementById("move").style.top = y.toString()+"px";
			document.getElementById("pozxmove").style.left = x.toString()+"px";
			document.getElementById("pozymove").style.top = y.toString()+"px";
			console.log(1);
			}
	}
	
	function pust(){
		move = 0;
		mapX = parseInt($('#move').css("left").replace("px",""));
		mapY = parseInt($('#move').css("top").replace("px",""));
		clearTimeout(kliktimer);
		setTimeout(function(){klik = 1;},50);
		$("body").css({'cursor': 'default','user-select': 'all' } );
		clearInterval(nacitani);
		mapa_load();
	}
	
	function mapa_clear(){
		while(nactenoi>50){
			var c = nacteno.shift();
			if(nactenovi.indexOf(c) != -1){
				nacteno.push(c);
			}else{
				$("#m"+c).remove();
				nactenoi=nactenoi-1;
			}
		}
	}
	
	function mapa_nacti(x,y){
		if(nacteno.indexOf(x.toString()+"_"+y.toString()) == -1){
			nacteno.push(x.toString()+"_"+y.toString());
			nactenoi=nactenoi+1;
			$.ajax({url: "ajax/mapa.php?x="+x+"&y="+y, success: function(data){ 
			var json = eval("(" + data + ")");
				var left = x*100;
				var top = (-y-1)*100;
				$("#move").append("<div id='m"+x.toString()+"_"+y.toString()+"' class='mapblok' style='position: absolute;left: "+left.toString()+"%;top:"+top.toString()+"%'></div>")
				$("#m"+x.toString()+"_"+y.toString()).fadeOut(0);
				$("#m"+x.toString()+"_"+y.toString()).fadeIn(500);
				var z = 0;
				while(z<100){
					if(json[z][2] == 0){
						var cl = "nic";
					}
					if(json[z][2] == 1){
						var cl= "mesto";
					}
					if(json[z][2] == 2){
						var cl = "les";
					}
					if(json[z][2] == 3){
						var cl= "kopec";
					}
					poleinfo[json[z][3]] = json[z];
					$("#m"+x.toString()+"_"+y.toString()).append("<div class='"+cl+"'><span class='klik' id='m"+json[z][3].toString()+"' title=''></span></div>");
						if(json[z][2] == 1){
							$("#m"+json[z][3].toString()).on('touchend click', function(){
								if(klik == 1){
									if(mesto == parseInt($(this).attr('id').toString().replace("m",""))){
										page_go("mesto");
									}else{
										page_go("mestoinfo&id="+$(this).attr('id').toString().replace("m",""));
									}
								}
							});
						}
				z = z+1;
				}
			$(".mesto").tooltip({
				track: true,
				content: function(){
					var id = parseInt($(this).attr('id').toString().replace("m",""));
					return "<b>"+poleinfo[id][4]+" ("+poleinfo[id][0]+"/"+poleinfo[id][1]+")</b><br>Hráč: "+poleinfo[id][5]+"<br>Počet obyvatel: "+poleinfo[id][6];
				},
				hide: { effect: "blind", duration: 0 }
			});
				
				
			}});
		}
	}
	
		function mapa_poz(x,y){
			if(nactenox.indexOf(x) == -1){
				nactenox.push(x);
				var left = x*100;
				$("#pozxmove").append("<div id='mx"+x.toString()+"' class='poz' style='position: absolute;left: "+left.toString()+"%'></div>")
				var z = x*10;
				while(z<(x+1)*10){
					$("#mx"+x.toString()).append("<div>"+z.toString()+"</div>");
					z = z+1;
				}
			}
			if(nactenoy.indexOf(y) == -1){
				nactenoy.push(y);
				var top = (-y-1)*100;
				$("#pozymove").append("<div id='my"+y.toString()+"' class='poz' style='position: absolute;top: "+top.toString()+"%'></div>")
				var z = (y+1)*10-1;
				while(z>y*10-1){
					$("#my"+y.toString()).append("<div><span>"+z.toString()+"</span></div>");
					z = z-1;
				}
			}
		}


	function mapa_load(){
		mapa_clear();
		var sirka = parseInt($("#back").css("width").replace("px",""));
		var vyska = parseInt($("#back").css("height").replace("px",""));
		if(sirka > 2000){
			sirka = 2000;
		}
		if(vyska > 2000){
			vyska = 2000;
		}
		var levo = -parseInt($("#move").css("left").replace("px",""));
		var pravo = levo+sirka;
		var nahore = parseInt($("#move").css("top").replace("px",""));
		var dole = nahore-vyska;
		levo = Math.floor(levo/velikost);
		pravo = Math.floor(pravo/velikost);
		nahore = Math.floor(nahore/velikost);
		dole = Math.floor(dole/velikost);
		var x = levo;
		var y = dole;
		nactenovi = new Array();
		while(x<=pravo){
			y = dole;
			while(y<=nahore){
				mapa_nacti(x,y);
				nactenovi.push(x.toString()+"_"+y.toString());
				mapa_poz(x,y);
				y++;
			}
			x++;
		}
	}
	
	function mapa_pozice(sx,sy){
		var sirka = parseInt($("#back").css("width").replace("px",""));
		var vyska = parseInt($("#back").css("height").replace("px",""));
		mapX = sirka/2-velikost/10*sx-velikost/10/2;
		mapY = vyska/2+velikost/10*sy+velikost/10/2;
		$("#move").animate({
			left: mapX,
			top: mapY
		}, 1000, function() {
			mapa_load();
		});
		$("#pozxmove").animate({
			left: mapX,
		}, 1000);
		$("#pozymove").animate({
			top: mapY
		}, 1000);
	}
	
	function mapa_pozices(sx,sy){
		$("#move").fadeOut(0);
		$("#move").fadeIn(1000);
		var sirka = parseInt($("#back").css("width").replace("px",""));
		var vyska = parseInt($("#back").css("height").replace("px",""));
		mapX = sirka/2-velikost/10*sx-velikost/10/2;
		mapY = vyska/2+velikost/10*sy+velikost/10/2;
		document.getElementById("move").style.left = mapX.toString()+"px";
		document.getElementById("move").style.top = mapY.toString()+"px";
		
		document.getElementById("pozxmove").style.left = mapX.toString()+"px";
		document.getElementById("pozymove").style.top = mapY.toString()+"px";
			mapa_load();
	}
	mapa_pozices(<?php echo $mesto["x"].",".$mesto["y"];?>);
</script>
		<div id="celek">
			<div id="obsah">
				<div id="obsah_h">
					<?php include "ajax/hra/mesto.php";?>
				</div>
			</div>
		</div>
	</body>