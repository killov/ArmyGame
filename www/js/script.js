function login_load(x){
	$("#obsah_h").load("index.php?p="+x,function(data){
		$("#obsah_h").html(data);
	});
}
var time_rozdil;
var data;
var load;
var url = "mesto";
var g_odpocitavac;
var g_odpocitavacv = new Array();
var g_odpocitavacvtime;
var g_odpocitavacp;
var g_odp;
var g_odpv;
var g_odpp;
var map = 0;


        
$(document).ready(function(){
    window.onpopstate = function(e) {
        page_gog(location.pathname.replace(dir,"")? location.pathname.replace(dir,"") : "mesto");
        console.log("sdsd");
    };    
});        

Date.now = function() { return new Date().getTime(); }
function odpocitejv(){
    var d = new Date();
    for(var i in g_odpocitavacv){
        var time = g_odpocitavacv[i]-((Date.now()+time_rozdil)/ 1000 | 0);
        if (time >= 0){
            var h,m,s;
            
            h = Math.floor(time/3600);
            m = Math.floor(time%3600/60);
            s = time%60;
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
            //g_odpocitavacv[i]--;
            if(time <= 0){
                data_load();
                page_refresh();
                return false;
            }
            
        }
    }
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

function timer(f){
    f(); 
    g_odp = setTimeout(function(){
        timer(f);
    },1000);
}

function cas(time){
    var h,m,s
    h = Math.floor(time/3600);
    m = Math.floor(time%3600/60);
    s = time%60;
    if(h<10){
    h = "0"+h;
    }
    if(m<10){
            m = "0"+m;
    }
    if(s<10){
            s = "0"+s;
    }
    return h+":"+m+":"+s;
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
    $.ajax({url: dir+"index.php?p="+x+"&a", success: function(result){
        data = result;
		if(load == 2){
                    window.history.pushState({}, "Armygame", dir+url);
			g_odpocitavac = -1;
			clearTimeout(g_odp);
			clearTimeout(g_odpv);
			$("#obsah_h").fadeOut(0);
			$("#obsah_h").html(data);
                        setlinks();
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
            window.history.pushState({}, "Armygame", dir+url);
		g_odpocitavac = -1;
		clearTimeout(g_odp);
		clearTimeout(g_odpv);
		$("#obsah_h").fadeOut(0);
		$("#obsah_h").html(data);
                setlinks();
		$("#obsah_h").fadeIn(500);
		hidemap();
	}else{
		load = 2;
	}
}

function page_refresh(){
	$.ajax({url: dir+"index.php?p="+url+"&a", success: function(result){
		g_odpocitavac = -1;
		clearTimeout(g_odp);
		clearTimeout(g_odpv);

		$("#obsah_h").html(result);
                setlinks();
	}});
}

function page_go(x){
	url = x;
        window.history.pushState({}, "Armygame", dir+url);
	$.ajax({url: dir+"index.php?p="+x+"&a", success: function(result){
		g_odpocitavac = -1;
		clearTimeout(g_odp);
		clearTimeout(g_odpv);
		$("#obsah_h").hide();
		$("#obsah_h").html(result);
                setlinks();
		$("#obsah_h").fadeIn(500);
		hidemap();
                
	}});
}

function page_gog(x){
	url = x;
	$.ajax({url: dir+"index.php?p="+x+"&a", success: function(result){
		g_odpocitavac = -1;
		clearTimeout(g_odp);
		clearTimeout(g_odpv);
		$("#obsah_h").hide();
		$("#obsah_h").html(result);
                setlinks();
		$("#obsah_h").fadeIn(500);
		hidemap();
                
	}});
}

function setlinks(){
    $(".link").click(function(e){
        e.preventDefault();
    })
    .mousedown(function(e){
        e.preventDefault();
        page_load($(this).attr("h"));
    }).mouseup(function(e){
        e.preventDefault();
        page_draw();
    });
}

var rtime;
function data_load(){
    clearTimeout(rtime);
	$.ajax({url: dir+"index.php?post=mestodata", success: function(data){ 
		var json = eval("(" + data + ")");
                var penize = json["penize"];
		surovina1 = json["surovina1"];
		surovina2 = json["surovina2"];
		surovina3 = json["surovina3"];
		surovina4 = json["surovina4"];
		surovina1_p = json["surovina1_produkce"];
		surovina2_p = json["surovina2_produkce"];
		surovina3_p = json["surovina3_produkce"];
		surovina4_p = json["surovina4_produkce"];
		sklad = json["sklad"];
                $("#surovina0").text(penize.toString());
		$("#surovina1").text(surovina1.toString());
		$("#surovina2").text(surovina2.toString());
		$("#surovina3").text(surovina3.toString());
		$("#surovina4").text(surovina4.toString());
		$("#surovina1_p").text(surovina1_p.toString());
		$("#surovina2_p").text(surovina2_p.toString());
		$("#surovina3_p").text(surovina3_p.toString());
		$("#surovina4_p").text(surovina4_p.toString());
		$("#sklad").text(sklad.toString());
                $("#jednotky").html(json["jednotky"]);


                
                rtime = setTimeout("data_load()", json["refresh"]*1000);
	}});
}

function postav(x){
	$.post(dir+"index.php?post=postav",{bid: x},function(){
		data_load();
		page_refresh();
	});
}

function vyzkum(x){
	$.post(dir+"index.php?post=vyzkum",{vid: x},function(){
		data_load();
		page_refresh();
	});
}

function jednotky_vyzkum(x){
	$.post(dir+"index.php?post=jednotky_vyzkum",{jid: x},function(){
		data_load();
		page_refresh();
	});
}

function pozvankazrusit(x){
	$.post(dir+"index.php?post=statsmazatpozvanku",{id: x},function(){
		page_refresh();
	});
}

function pozvankapotvrdit(x){
	$.post(dir+"index.php?post=statpotvrditpozvanku",{id: x},function(data){
                var d = JSON.parse(data);
                stat = d[0];
		page_refresh();
	});
}
function opustitstat(){
	$.post(dir+"index.php?post=opustitstat",function(){
            stat = 0;
		page_go("stat");
	});
}

function formular_upload(form,kam,callback){
	$(form).submit(function(ev) {
	$.post(dir+kam, $(this).serialize(), function(data){ 
    var json = eval("(" + data + ")");
	callback(json);
	});
        ev.preventDefault();
	})
}

function produkce(){
	surovina1 = surovina1+surovina1_p/3600;
	if(surovina1>sklad){
		surovina1 = sklad;
	}
	surovina2 = surovina2+surovina2_p/3600;			
	if(surovina2>sklad){
		surovina2 = sklad;
	}
	surovina3 = surovina3+surovina3_p/3600;
	if(surovina3>sklad){
		surovina3 = sklad;
	}
	surovina4 = surovina4+surovina4_p/3600;
	if(surovina4>sklad){
		surovina4 = sklad;
	}
        if(surovina4<0){
            data_load();
        }else{
            $("#surovina1").text(Math.floor(surovina1).toString());
            $("#surovina2").text(Math.floor(surovina2).toString());
            $("#surovina3").text(Math.floor(surovina3).toString());
            $("#surovina4").text(Math.floor(surovina4).toString());
        }
}

function mapa(){
	if(map){
		mapa_pozices(mesto_x,mesto_y,0);
	}else{
		showmap();
	}
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
	$.post(dir+"ajax/info/trziste_exist.php", {x:x,y:y}, function(data){
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

var Trh = function(){
    this.pomer = 1;
    this.nosnost = 100;
    this.prepocet = function(){
        var soucet = 0;
        if(!isNaN(parseInt($("#sur1").val()))){
            soucet += parseInt($("#sur1").val());
        }
        if(!isNaN(parseInt($("#sur2").val()))){
            soucet += parseInt($("#sur2").val());
        }
        if(!isNaN(parseInt($("#sur3").val()))){
            soucet += parseInt($("#sur3").val());
        }
        if(!isNaN(parseInt($("#sur4").val()))){
            soucet += parseInt($("#sur4").val());
        }
        if(this.typ){
            $("#sur0").html(Math.floor(soucet/this.pomer));
        }else{
            $("#sur0").html(Math.floor(soucet*this.pomer));
        }
        $("#obch").html(Math.ceil(soucet/this.nosnost));
    }
    this.zmena = function(){
        this.typ = this.typ?0:1;
        if(this.typ == 0){
            $("#zm").html($("#zm1").html());
            $('#arr').css('transform','rotate(0deg)');
        }else{
            $("#zm").html($("#zm2").html());
            $('#arr').css('transform','rotate(180deg)');
        }
        $("#typ").val(this.typ);
        this.prepocet();
        
        
        
    }
}

function jednotky(id,s1,s2,s3,s4,cass,spotreba){
    var pocet = parseInt($("#pocet"+id).val()); 
    if(isNaN(pocet) || pocet <= 0){
        if(s1) $("#s1"+id).html(s1);
        if(s2) $("#s2"+id).html(s2);
        if(s3) $("#s3"+id).html(s3);
        if(s4) $("#s4"+id).html(s4);
        $("#cas"+id).html(cas(cass));
        $("#spotreba"+id).html(spotreba);
    }else{
        if(s2) $("#s1"+id).html(s1*pocet);
        if(s2) $("#s2"+id).html(s2*pocet);
        if(s3) $("#s3"+id).html(s3*pocet);
        if(s4) $("#s4"+id).html(s4*pocet);
        $("#cas"+id).html(cas(cass*pocet));
        $("#spotreba"+id).html(spotreba*pocet);
    }
}