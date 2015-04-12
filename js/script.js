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
var g_odp;
var map = 0;

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


function page_load(x){
	url = x;
	data = "";
	load = 0;
    $.ajax({url: "ajax/hra.php?p="+x, success: function(result){
        data = result;
		if(load == 2){
			g_odpocitavac = -1;
			clearTimeout(g_odp);
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

function mapa(){
	if(map){
		mapa_pozice(mesto_x,mesto_y);
	}else{
		showmap();
	}
}
