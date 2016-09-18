	var move = 0;
	var mysX, mysY, mapX, mapY, x, y;
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
        
        for(var x = -200;x<200;x++){
            poleinfo[x] = [];
            for(var y = -200;y<200;y++){
                poleinfo[x][y] = false;
            }
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
window.onresize = function(event) {
    mapload();
    mapa_load();
};
function mapload(){
    mapX = parseInt($('#move').css("left"));
    mapY = parseInt($('#move').css("top"));
    sirka = parseInt($("#back").css("width"));
    vyska = parseInt($("#back").css("height"));
}
function tahni(e,zo){
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
        mysX = e.clientX;
        mysY = e.clientY;
        
        $("body").css( {'cursor': 'move','user-select': 'none' });
        nacitani = setInterval("mapa_load()",500);
}

var last = 0;
var akt = 0;
var lastt = -1;
var rozdilX = 0, rozdilY = 0;

function pohyb(e){
    if(move){
        if(e.clientX-mysX > 5 || e.clientY-mysY > 5){
                klik = 0;
        }
        rozdilX = e.clientX-mysX;
        rozdilY = e.clientY-mysY;
        x = mapX+rozdilX;
        y = mapY+rozdilY;
        document.getElementById("move").style.left = x.toString()+"px";
        document.getElementById("pozxmove").style.left = x.toString()+"px";	
        document.getElementById("move").style.top = y.toString()+"px";
        document.getElementById("pozymove").style.top = y.toString()+"px";
    }
    if(!move){                        
        var x = (e.clientX-mapX-200)*(1000/velikost);
        var y = (e.clientY-mapY-44)*(1000/velikost);
        x = Math.floor(x/100);
        y = -Math.floor(y/100)-1;
        var z = y*10+x;

        var data = poleinfo[x][y];
        if(data){
            var st = data[8]!=0?"<br>Stát: "+data[9]:"";
            akt = data[8];
            if(last != data[8]){
                if(last == stat){
                    $(".sth"+last).css("stroke","blue");
                }else{
                    $(".sth"+last).css("stroke","red");
                }
                $(".sth"+data[8]).css("stroke","white");
                last = data[8];
            }
            if(lastt != data[3]){
                if(data[2] == 1){
                    $("#back").tooltip("option", "content", "<b>"+data[4]+" ("+data[0]+"/"+data[1]+")</b><br>Hráč: "+data[5]+"<br>Počet obyvatel: "+data[6]+st);
                }else{
                    $("#back").tooltip("option", "content", "<b>Volné pole ("+data[0]+"/"+data[1]+")</b>"+st);
                }
                lastt = data[3];
            }
        }
    }
}
	
function pust(e){
    move = 0;
    rozdilX = 0;
    rozdilY = 0;
    mapX = parseInt($('#move').css("left"));
    mapY = parseInt($('#move').css("top"));
    clearTimeout(kliktimer);
    setTimeout(function(){klik = 1;},50);
    $("body").css({'cursor': 'auto','user-select': 'text' } );
    clearInterval(nacitani);
    mapa_load();

    if(klik == 1){
        var x = (e.clientX-mapX-200)*(1000/velikost);
        var y = (e.clientY-mapY-44)*(1000/velikost);
        x = Math.floor(x/100);
        y = -Math.floor(y/100)-1;
        var data = poleinfo[x][y];
        if(data[2] == 1){
            if(mesto == data[3]){
                page_go("mesto");
            }else{
                page_go("mestoinfo/"+data[3]);
            }
        } 
    }
    mapa_clear();
}

function mapa_nacti(json,x,y){
    var left = x*100;
    var top = (-y-1)*100;
    $("#move").append("<div id='m"+x+"_"+y+"' class='mapblok' style='position: absolute;left: "+left.toString()+"%;top:"+top.toString()+"%' title=''></div>");
    vlozsvg(x,y,json);
    $("#m"+x+"_"+y)
        .css("background-image","url("+dir+"mapacache/"+x+"_"+y+"_"+json[100]+".jpg?")
        .waitForImages(function() {
            $(this).fadeOut(0)
                .fadeIn(0);
        });
}

function vlozsvg(x,y,json){
    var f = "";
    var z = 0;
    while(z<100){
        var l = (z%10)*100;
        var t = Math.floor(z/10)*100;
        if(json[z][8] == akt){
            var barva = "white";
        }else if(json[z][8] == stat){
            var barva = "blue";
        }else{
            var barva = "red";
        }
        if(json[z][10] == 1){
            f += '<line x1="'+(l+97)+'" y1="'+(t-3)+'" x2="'+(l+97)+'" y2="'+(t+103)+'" style="stroke:'+barva+';stroke-width:3" class="sth'+json[z][8]+'" />';    
        }
        else if(json[z][10] == 2){
            f += '<line x1="'+(l-3)+'" y1="'+(t+97)+'" x2="'+(l+103)+'" y2="'+(t+97)+'" style="stroke:'+barva+';stroke-width:3" class="sth'+json[z][8]+'" />';    
        }
        else if(json[z][10] == 3){
            f += '<path d="M'+(l+97)+','+(t-3)+' q0,100 -100,100" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />';    
        }
        else if(json[z][10] == 4){
            f += '<line x1="'+(l+3)+'" y1="'+(t-3)+'" x2="'+(l+3)+'" y2="'+(t+103)+'" style="stroke:'+barva+';stroke-width:3" class="sth'+json[z][8]+'" />';    
        }
        else if(json[z][10] == 5){
            f += '<line x1="'+(l+97)+'" y1="'+(t-3)+'" x2="'+(l+97)+'" y2="'+(t+103)+'" style="stroke:'+barva+';stroke-width:3" class="sth'+json[z][8]+'" />';    
            f += '<line x1="'+(l+3)+'" y1="'+(t-3)+'" x2="'+(l+3)+'" y2="'+(t+103)+'" style="stroke:'+barva+';stroke-width:3" class="sth'+json[z][8]+'" />';    
        }
        else if(json[z][10] == 6){
            f += '<path d="M'+(l+103)+','+(t+97)+' q-100,0 -100,-100" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />';    
        }
        else if(json[z][10] == 7){
            f += '<path d="M'+(l+97)+','+(t-3)+' q0,100 -50,100 q-44,0 -44,-100" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />';    
        }
        else if(json[z][10] == 8){
            f += '<line x1="'+(l-3)+'" y1="'+(t+3)+'" x2="'+(l+103)+'" y2="'+(t+3)+'" style="stroke:'+barva+';stroke-width:3" class="sth'+json[z][8]+'" />';    
        }
        else if(json[z][10] == 9){
            f += '<path d="M'+(l-3)+','+(t+3)+' q100,0 100,100" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />';    
        }
        else if(json[z][10] == 10){
            f += '<line x1="'+(l-3)+'" y1="'+(t+3)+'" x2="'+(l+103)+'" y2="'+(t+3)+'" style="stroke:'+barva+';stroke-width:3" class="sth'+json[z][8]+'" />';    
            f += '<line x1="'+(l-3)+'" y1="'+(t+97)+'" x2="'+(l+103)+'" y2="'+(t+97)+'" style="stroke:'+barva+';stroke-width:3" class="sth'+json[z][8]+'" />'; 
        }
        else if(json[z][10] == 11){
            f += '<path d="M'+(l-3)+','+(t+3)+' q100,0 100,50 q0,44 -100,44" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />';    
        }
        else if(json[z][10] == 12){
            f += '<path d="M'+(l+3)+','+(t+103)+' q0,-100 100,-100" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />';    
        }
        else if(json[z][10] == 13){
            f += '<path d="M'+(l+3)+','+(t+103)+' q0,-100 50,-100 q44,0 44,100" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />';    
        }
        else if(json[z][10] == 14){
            f += '<path d="M'+(l+103)+','+(t+97)+' q-100,0 -100,-50 q0,-44 100,-44" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />';    
        }
        else if(json[z][10] == 15){
            f += '<circle cx="'+(l+50)+'" cy="'+(t+50)+'" r="50" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />';    
        }
        z++;
    }
    if(f){
        $("#m"+x+"_"+y).html("<svg id='svg"+x+"_"+y+"' height='100%' width='100%' viewBox='0 0 1000 1000'>"+f+"</svg>");
    }else{
        $("#m"+x+"_"+y).html("");
    }
}
		function mapa_poz(x,y){
			if(nactenox.indexOf(x) == -1){
				nactenox.push(x);
				var left = x*100;
				$("#pozxmove").append("<div id='mx"+x+"' class='poz' style='position: absolute;left: "+left+"%'></div>")
				var z = x*10;
				while(z<(x+1)*10){
					$("#mx"+x.toString()).append("<div>"+z+"</div>");
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

	
	function mapa_clear(){ 
            for(var i in nacteno){
                if(nactenovi.indexOf(nacteno[i]) == -1){
                    $("#m"+nacteno[i].toString()).remove();
                }
            }
            nacteno = nactenovi;

            for(var i in nactenox){
                if(nactenovix.indexOf(nactenox[i]) == -1){
                    $("#mx"+nactenox[i].toString()).remove();
                }
            }
            nactenox = nactenovix;

            for(var i in nactenoy){
                if(nactenoviy.indexOf(nactenoy[i]) == -1){
                    $("#my"+nactenoy[i].toString()).remove();
                }
            }
            nactenoy = nactenoviy;
	}
var sirka, vyska;

var xf,yf,nactenovix = [],nactenoviy = [];
function mapa_load(){
    var levo = -(mapX+rozdilX);
    var pravo = levo+sirka;
    var nahore = mapY+rozdilY;
    var dole = nahore-vyska;
    levo = Math.floor(levo/velikost);
    pravo = Math.floor(pravo/velikost);
    nahore = Math.floor(nahore/velikost);
    dole = Math.floor(dole/velikost);
    var x = levo;
    var y;
    nactenovi = [];
    nactenovix = [];
    nactenoviy = [];

    var f = []; 
    var g = false;
    while(x<=pravo){
        y = dole;
        while(y<=nahore){
            if(x <= 19 && x >= -20 && y <= 19 && y >= -20){
                if(nacteno.indexOf(x.toString()+"_"+y.toString()) == -1){
                    nacteno.push(x.toString()+"_"+y.toString());
                    if(mapasez.indexOf(x.toString()+"_"+y.toString()) == -1){
                        f.push(Array(x,y));
                        g = true;
                    }else{
                        mapa_nacti(mapacache[x.toString()+"_"+y.toString()],x,y);
                    }				
                }
                nactenovi.push(x+"_"+y);
                mapa_poz(x,y);
            }
            y++;
        }
        x++;
    }
    x = levo;
    while(x<=pravo){
        nactenovix.push(x);
        x++;
    }
    y = dole;
    while(y<=nahore){
        nactenoviy.push(y);
        y++;
    }
    if(g){
        $.ajax({url: dir+"index.php?post=mapa&x="+JSON.stringify(f), success: function(data){ 
            var json = eval("(" + data + ")");
            for(var x in json){
                for(var y in json[x]){
                    mapa_nacti(json[x][y],x,y);
                    mapasez.push(x+"_"+y);
                    mapacache[x+"_"+y] = json[x][y];
                    for(var z = 0;z<100;z++){
                        poleinfo[json[x][y][z][0]][json[x][y][z][1]] = json[x][y][z];
                    }
                }
            }				
        }});
    }
}
               
function mapa_obnov(bloky){
    var nacti = [];
    for(var i in bloky){
        console.log(bloky[i]);
        var index = mapasez.indexOf(bloky[i]);
        if(index != -1){
            mapasez.splice(index,1);
        }
        if(nactenovi.indexOf(bloky[i][0]+"_"+bloky[i][1]) != -1){
            nacti.push(bloky[i]);
        }
    }
    
            $.ajax({url: dir+"index.php?post=mapa&x="+JSON.stringify(nacti), success: function(data){ 
                var json = eval("(" + data + ")");
                for(var x in json){
                    for(var y in json[x]){
                        vlozsvg(x,y,json[x][y]);
                        $("#m"+x+"_"+y).css("background-image","url("+dir+"mapacache/"+x+"_"+y+"_"+json[x][y][100]+".jpg?");
                        mapasez.push(x.toString()+"_"+y.toString());
                        mapacache[x.toString()+"_"+y.toString()] = json[x][y];
                        for(var z = 0;z<100;z++){
                            poleinfo[json[x][y][z][0]][json[x][y][z][1]] = json[x][y][z];
                        }
                    }
                }				
            }});
        }
	
	function mapa_pozice(sx,sy){
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

		mapX = (sirka-sir)/2-velikost/10*sx-velikost/10/2;
		mapY = vyska/2+velikost/10*sy+velikost/10/2;
		document.getElementById("move").style.left = mapX.toString()+"px";
		document.getElementById("move").style.top = mapY.toString()+"px";
		document.getElementById("pozxmove").style.left = mapX.toString()+"px";
		document.getElementById("pozymove").style.top = mapY.toString()+"px";
		mapa_load();
	}