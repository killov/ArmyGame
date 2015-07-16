function login_load(x){
	$("#obsah_h").load("ajax/login.php?p="+x,function(){
		$("#obsah_h").fadeOut(0);
		$("#obsah_h").fadeIn(1000);
	});
}

var data;
var load;
var url = "mesto";
var g_odpocitavac;
var g_odpocitavacv = new Array();
var g_odpocitavacp;
var g_odp;
var g_odpv;
var g_odpp;
var map = 0;

	var move = 0;
	var mysX, mysY, mapX, mapY, x, y
	var nacteno = new Array();
	var mapasez = new Array();
	var mapacache = new Array();
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

function odpocitejv(){
	$.each(g_odpocitavacv, function( i, val ) {
		if (val >= 0){
			var h,m,s
			h = Math.floor(val/3600);
			m = Math.floor(val%3600/60);
			s = val%60;
			if(h<10){
			h = "0"+h;
			}
			if(m<10){
				m = "0"+m;
			}
			if(s<10){
				s = "0"+s;
			}
			$("#odpv"+i.toString()).text(h+":"+m+":"+s);
			if(val == 0){
				data_load();
				page_refresh();
			}
			g_odpocitavacv[i] = val-1;
		}
	});
	g_odpv = setTimeout("odpocitejv()",1000);
}	
	
function odpocitej(){
	if (g_odpocitavac >= 0){
		var h,m,s
		h = Math.floor(g_odpocitavac/3600);
		m = Math.floor(g_odpocitavac%3600/60);
		s = g_odpocitavac%60;
		if(h<10){
		h = "0"+h;
		}
		if(m<10){
			m = "0"+m;
		}
		if(s<10){
			s = "0"+s;
		}
		$("#odpocet").text(h+":"+m+":"+s);
		if(g_odpocitavac == 0){
			data_load();
			page_refresh();
		}else{
			g_odpocitavac = g_odpocitavac-1;
			g_odp = setTimeout("odpocitej()",1000);
		}
	}
}

function odpocitejp(){
	if (g_odpocitavacp >= 0){
		var h,m,s
		h = Math.floor(g_odpocitavacp%86400/3600);
		m = Math.floor(g_odpocitavacp%3600/60);
		s = g_odpocitavacp%60;
		if(h<10){
		h = "0"+h;
		}
		if(m<10){
			m = "0"+m;
		}
		if(s<10){
			s = "0"+s;
		}
		$("#odpocetp").text(h+":"+m+":"+s);
		g_odpocitavacp = g_odpocitavacp+1;
		g_odpp = setTimeout("odpocitejp()",1000);
	}
}


function page_load(x){
	url = x;
	data = "";
	load = 0;
    $.ajax({url: "ajax/hra.php?p="+x, success: function(result){
        data = result;
		if(load == 2){
			g_odpocitavac = -1;
			clearTimeout(g_odp);
			clearTimeout(g_odpv);
			$("#obsah_h").fadeOut(0);
			$("#obsah_h").html(data);
			$("#obsah_h").fadeIn(500);
			hidemap();
		}else{
			load = 1;
		}
	}
	});
}

function page_draw(){
	if(load == 1){
		g_odpocitavac = -1;
		clearTimeout(g_odp);
		clearTimeout(g_odpv);
		$("#obsah_h").fadeOut(0);
		$("#obsah_h").html(data);
		$("#obsah_h").fadeIn(500);
		hidemap();
	}else{
		load = 2;
	}
}

function page_refresh(){
	$.ajax({url: "ajax/hra.php?p="+url, success: function(result){
		g_odpocitavac = -1;
		clearTimeout(g_odp);
		clearTimeout(g_odpv);
		$("#obsah_h").fadeOut(0);
		$("#obsah_h").html(result);
		$("#obsah_h").fadeIn(500);
	}});
}

function page_go(x){
	url = x;
	$.ajax({url: "ajax/hra.php?p="+x, success: function(result){
		g_odpocitavac = -1;
		clearTimeout(g_odp);
		clearTimeout(g_odpv);
		$("#obsah_h").hide();
		$("#obsah_h").html(result);
		$("#obsah_h").fadeIn(500);
		hidemap();
	}});
}

function data_load(){
	$.ajax({url: "ajax/mestodata.php", success: function(data){ 
		var json = eval("(" + data + ")");
		drevo = json["drevo"];
		kamen = json["kamen"];
		zelezo = json["zelezo"];
		obili = json["obili"];
		drevo_p = json["drevo_produkce"];
		kamen_p = json["kamen_produkce"];
		zelezo_p = json["zelezo_produkce"];
		obili_p = json["obili_produkce"];
		sklad = json["sklad"];
		if(json["zpravy"] == 1){
			$(".zpravy").addClass("zpravyn");
		}
		$("#drevo").text(drevo.toString());
		$("#kamen").text(kamen.toString());
		$("#zelezo").text(zelezo.toString());
		$("#obili").text(obili.toString());
		$("#drevo_p").text(drevo_p.toString());
		$("#kamen_p").text(kamen_p.toString());
		$("#zelezo_p").text(zelezo_p.toString());
		$("#obili_p").text(obili_p.toString());
		$("#sklad").text(sklad.toString());
	}});
}

function postav(x){
	$.post("ajax/zpracuj/postav.php",{bid: x},function(){
		data_load();
		page_refresh();
	});
}

function formular_upload(form,kam,callback){
	$(form).submit(function() {
	$.post(kam, $(this).serialize(), function(data){ 
    var json = eval("(" + data + ")");
	callback(json);
	});
	})
}

function produkce(){
	drevo = drevo+drevo_p/3600;
	if(drevo>sklad){
		drevo = sklad;
	}
	kamen = kamen+kamen_p/3600;			
	if(kamen>sklad){
		kamen = sklad;
	}
	zelezo = zelezo+zelezo_p/3600;
	if(zelezo>sklad){
		zelezo = sklad;
	}
	obili = obili+obili_p/3600;
	if(obili>sklad){
		obili = sklad;
	}
	$("#drevo").text(Math.floor(drevo).toString());
	$("#kamen").text(Math.floor(kamen).toString());
	$("#zelezo").text(Math.floor(zelezo).toString());
	$("#obili").text(Math.floor(obili).toString());
}

function showmap(){
	$("#celek").fadeOut(500);

	map = 1;
}

function hidemap(){
	if(map){
		$("#celek").fadeIn(500);
		map = 0;
	}
}	

var pravo;
function showpravo(){
	$("#pravej").fadeIn(500);
	pravo = 1;
}

function hidepravo(){
	$("#pravej").fadeOut(500);
	pravo = 0;
}	

function poslat_suroviny(x,y){
	$.post("ajax/info/trziste_exist.php", {x:x,y:y}, function(data){
		var json = eval("(" + data + ")");
		if(json[0]){
			showpravo();
			pravo_go("obchod&x="+x+"&y="+y);
			showmap();
			draw_trziste_cesta(mesto_x,mesto_y,x,y);
		}
	});
}

function poslat_suroviny_close(){
	hidepravo();
	$("#cesta").remove();
}

function draw_trziste_cesta(x0,y0,x1,y1){
	var sirka = (Math.abs(x0-x1)+1)*100;
	var vyska = (Math.abs(y0-y1)+1)*100;
	var sx = (x0+x1)/2;
	var sy = (y0+y1)/2;
	var sirkap = sirka/10;
	var vyskap = vyska/10;
	if(x0<x1){
		var left = x0*10;
		x1 = x1-x0;
		x0 = 0;
		
	}else{
		var left = x1*10;
		x0 = x0-x1;
		x1 = 0;
	}
	if(y0>y1){
		var top = -(y0+1)*10;
		y1=y0-y1;
		y0 = 0;
	}else{
		var top = -(y1+1)*10;
		y0=y1-y0;
		y1 = 0;
	}
	$("#cesta").remove();
	$("#move").append("<canvas id='cesta' width='"+sirka+"' height='"+vyska+"' style='position: absolute;left: "+left.toString()+"%;top:"+top.toString()+"%;width: "+sirkap.toString()+"%;height:"+vyskap.toString()+"%'></canvas>")
	var c = document.getElementById("cesta");
	var ctx = c.getContext("2d");
	ctx.strokeStyle = 'rgba(240,255,255, 0.3)';
	ctx.lineCap = 'round';
	ctx.lineWidth = 10;
	ctx.moveTo(x0*100+50,y0*100+50);
	ctx.lineTo(x1*100+50,y1*100+50);
	ctx.moveTo(x1*100+50+45,y1*100+50);
	ctx.stroke();
	ctx.arc(x1*100+50,y1*100+50,45,Math.PI*0,2*Math.PI)
	ctx.fillStyle = 'rgba(240,255,255, 0.5)';
	ctx.fill();
	mapa_pozices(sx,sy,300);
}

function pravo_go(x){
	url =x;
	$.ajax({url: "ajax/pravo.php?p="+x, success: function(result){
		clearTimeout(g_odpp);
		$("#pravej div").html(result);
	}});
}

function mapa(){
	if(map){
		mapa_pozice(mesto_x,mesto_y);
	}else{
		showmap();
	}
}

function obchod_pricti(id,sur){
	if($(id).val()=="" || isNaN($(id).val())){
		$(id).val("0");
	}
	x = parseInt($(id).val())+1000;
	if(x>sur){
		$(id).val(Math.floor(sur.toString()));
	}else{
		$(id).val(x.toString());
	}
	obchod_obchodnici();
}

function obchod_obchodnici(){
	var x = 0;
	if(!(isNaN($("#obch_drevo").val()) || $("#obch_drevo").val()=="")){
		x = x+parseInt($("#obch_drevo").val())
	}
	if(!(isNaN($("#obch_kamen").val()) || $("#obch_kamen").val()=="")){
		x = x+parseInt($("#obch_kamen").val())
	}
	if(!(isNaN($("#obch_zelezo").val()) || $("#obch_zelezo").val()=="")){
		x = x+parseInt($("#obch_zelezo").val())
	}
	if(!(isNaN($("#obch_obili").val()) || $("#obch_obili").val()=="")){
		x = x+parseInt($("#obch_obili").val())
	}
	x = Math.ceil(x/1000);
	$("#obch_potreba").text(x);
}

function zoom(z,na,zx,zy){
		var sirka = parseInt($("#back").css("width").replace("px",""));
		var vyska = parseInt($("#back").css("height").replace("px",""));
		mapX = mapX*(na/z)+(sirka/2)*(1-na/z)+zx*0.1;
		mapY = mapY*(na/z)+(vyska/2)*(1-na/z)+zy*0.1;
		$("#move").stop().animate({
			width: velikost,
			height: velikost,
			left: mapX,
			top: mapY
			}, 1);
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
			var sirka = parseInt($("#back").css("width").replace("px",""));
			var vyska = parseInt($("#back").css("height").replace("px",""));
			x = mapX+(event.pageX-mysX);
			y = mapY+(event.pageY-mysY);
			if(20*velikost>=x && -20*velikost+sirka <= x){
				document.getElementById("move").style.left = x.toString()+"px";
				document.getElementById("pozxmove").style.left = x.toString()+"px";	
			}
			document.getElementById("move").style.top = y.toString()+"px";
			document.getElementById("pozymove").style.top = y.toString()+"px";
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
		console.log(nactenoi);
		while(nactenoi>40){
			var c = nacteno.shift();
			if(nactenovi.indexOf(c) != -1){
				nacteno.push(c);
			}else{
				$("#m"+c).remove();
				nactenoi=nactenoi-1;
			}
		}
	}
	
	function mapa_nacti(json,x,y){
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
						var pop = json[z][6];
						var pop_size = Math.floor(pop/36);
						var cl= "mesto mesto-"+pop_size;
					}
					if(json[z][2] == 2){
						var cl = "les-"+json[z][7];
					}
					if(json[z][2] == 3){
						var cl= "kopec-"+json[z][7];
					}
					poleinfo[json[z][3]] = json[z];
					var lleft = (z%10)*10;
					var ttop = Math.floor(z/10)*10-5;
					$("#m"+x.toString()+"_"+y.toString()).append("<div class='"+cl+"' style='left: "+lleft+"%;top:"+ttop+"%;'><span class='klik' id='m"+json[z][3].toString()+"' title=''></span></div>");
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
					var id = parseInt($(this).attr('id').replace("m",""));
					return "<b>"+poleinfo[id][4]+" ("+poleinfo[id][0]+"/"+poleinfo[id][1]+")</b><br>Hráč: "+poleinfo[id][5]+"<br>Počet obyvatel: "+poleinfo[id][6];
				},
				hide: { effect: "blind", duration: 0 }
			});	

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

var xf,yf,f;
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
		f = Array(); 
		var g = false;
		while(x<=pravo){
			y = dole;
			while(y<=nahore){
				if(nacteno.indexOf(x.toString()+"_"+y.toString()) == -1){
					nacteno.push(x.toString()+"_"+y.toString());
					nactenoi = nactenoi+1;
					if(mapasez.indexOf(x.toString()+"_"+y.toString()) == -1){
						f.push(Array(x,y));
						g = true;
					}else{
						mapa_nacti(mapacache[x.toString()+"_"+y.toString()],x,y);
					}				
				}
				nactenovi.push(x.toString()+"_"+y.toString());
				mapa_poz(x,y);
				y++;
			}
			x++;
		}
		if(g){
			$.ajax({url: "ajax/mapa.php?x="+JSON.stringify(f), success: function(data){ 
				var json = eval("(" + data + ")");
				for(var x in json){
					for(var y in json[x]){
						mapa_nacti(json[x][y],x,y);
						mapasez.push(x.toString()+"_"+y.toString());
						mapacache[x.toString()+"_"+y.toString()] = json[x][y];
					}
				}				
			}
			});
		}
	}
	
	function mapa_pozice(sx,sy){
		var sirka = parseInt($("#back").css("width").replace("px",""));
		var vyska = parseInt($("#back").css("height").replace("px",""));
		mapX = sirka/2-velikost/10*sx-velikost/10/2;
		mapY = vyska/2+velikost/10*sy+velikost/10/2+35;
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
		mapa_load();
	}
	
	function mapa_pozices(sx,sy,sir){
		$("#move").fadeOut(0);
		$("#move").fadeIn(1000);
		var sirka = parseInt($("#back").css("width").replace("px",""))-sir;
		var vyska = parseInt($("#back").css("height").replace("px",""));
		mapX = sirka/2-velikost/10*sx-velikost/10/2;
		mapY = vyska/2+velikost/10*sy+velikost/10/2+35;
		document.getElementById("move").style.left = mapX.toString()+"px";
		document.getElementById("move").style.top = mapY.toString()+"px";
		document.getElementById("pozxmove").style.left = mapX.toString()+"px";
		document.getElementById("pozymove").style.top = mapY.toString()+"px";
		mapa_load();
	}
	
	function hlaska(x,typ){
		if(typ == 1){
			$("#hlaska").css("background-color","green");
		}else{
			$("#hlaska").css("background-color","red");
		}
		$("#hlaska").text(x);
		$("#hlaska").css("display","block");
		setTimeout(function(){
			$("#hlaska").fadeOut(1000);
		},2000);
	}

