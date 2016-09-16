function mapa(){
	if(map){
		mapa_pozice(mesto_x,mesto_y);
	}else{
		showmap();
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

var nacitani;
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
		mapX = parseInt($('#move').css("left").replace("px",""));
		mapY = parseInt($('#move').css("top").replace("px",""));
		$("body").css( {'cursor': 'move','user-select': 'none' });
		nacitani = setInterval("mapa_load()",500);
		showmap();
                
              
	}
	
    function pohyb(e){
        if(move){
                if(e.clientX-mysX > 5 || e.clientY-mysY > 5){
                        klik = 0;
                }
                var sirka = parseInt($("#back").css("width").replace("px",""));
                var vyska = parseInt($("#back").css("height").replace("px",""));
                x = mapX+(e.clientX-mysX);
                y = mapY+(e.clientY-mysY);
                document.getElementById("move").style.left = x.toString()+"px";
                document.getElementById("pozxmove").style.left = x.toString()+"px";	
                document.getElementById("move").style.top = y.toString()+"px";
                document.getElementById("pozymove").style.top = y.toString()+"px";
        }
        if(!move){                        
            var x = (e.clientX-mapX-200)*(1000/velikost);
            var y = (e.clientY-mapY)*(1000/velikost);
            x = Math.floor(x/100);
            y = -Math.floor(y/100)-1;
            var z = y*10+x;
            //console.log(e);
            var data = poleinfo[x][y];

            var st = data[8]!=0?"<br>Stát: "+data[9]:"";
            
            if(data[2] == 1){
                $("#back").tooltip("option", "content", "<b>"+data[4]+" ("+data[0]+"/"+data[1]+")</b><br>Hráč: "+data[5]+"<br>Počet obyvatel: "+data[6]+st);
            }else{
                $("#back").tooltip("option", "content", "<b>Volné pole ("+data[0]+"/"+data[1]+")</b>"+st);
            }
        }
    }
	
	function pust(e){
		move = 0;
		mapX = parseInt($('#move').css("left").replace("px",""));
		mapY = parseInt($('#move').css("top").replace("px",""));
		clearTimeout(kliktimer);
		setTimeout(function(){klik = 1;},50);
		$("body").css({'cursor': 'default','user-select': 'all' } );
		clearInterval(nacitani);
		mapa_load();
                
                  if(klik == 1){
                    var x = (e.clientX-mapX-200)*(1000/velikost);
                    var y = (e.clientY-mapY)*(1000/velikost);
                    x = Math.floor(x/100);
                    y = -Math.floor(y/100)-1;
                    var data = poleinfo[x][y];
                    if(data[2] == 1){
                            if(mesto == data[3]){
                                    page_go("mesto");
                            }else{
                                    page_go("mestoinfo&id="+data[3].toString());
                            }
                    }
                    console.log(data);
                    mapa_clear();
            }
	}

	function mapa_nacti(json,x,y){
				var left = x*100;
				var top = (-y-1)*100;
				var lesy = [0,230,460,690,920,1150,1380,1610,1840,2070,2300,2530,2760,2990,3220,3450];
				var kopce = [0,230,460,690,920,1150,1380,1610,1840,2070,2300,2530,2760,2990,3220,3450];
				var mesta = [3680,3910,4140,4370,4600];
				$("#move").append("<div id='m"+x.toString()+"_"+y.toString()+"' class='mapblok' style='position: absolute;left: "+left.toString()+"%;top:"+top.toString()+"%' title=''></div>")
				$("#m"+x.toString()+"_"+y.toString()).fadeOut(0);
				$("#m"+x.toString()+"_"+y.toString()).fadeIn(500);
				var z = 0;
				var g = 0;
				$("#m"+x.toString()+"_"+y.toString()).append("<canvas id='c"+x.toString()+"_"+y.toString()+"' width='1000' height='1000'></canvas>");
				$("#m"+x.toString()+"_"+y.toString()).append("<div id='d"+x.toString()+"_"+y.toString()+"'></div>");
				var canvas = document.getElementById("c"+x.toString()+"_"+y.toString());
				var ctx = canvas.getContext("2d");
				ctx.fillStyle = "#72A645";
				ctx.fillRect(0,0,1000,1000);
				while(z<121){
					var lleft = (z%11)*100-100;
					var ttop = Math.floor(z/11)*100-75;
					if(json[z][2] == 0){
						var cl = "nic";
					}
					if(json[z][2] == 1){
						var pop = json[z][6];
						var pop_size = Math.floor(pop/100);
						var cl= "mesto mesto-"+pop_size;
						ctx.drawImage(imageObj, mesta[pop_size], 0, 230, 230, lleft, ttop, 200, 200);
					}
					if(json[z][2] == 2){
						ctx.drawImage(imageObj, lesy[json[z][7]], 0, 230, 230, lleft, ttop, 200, 200);
						var cl = "les-"+json[z][7];
					}
					if(json[z][2] == 3){
						ctx.drawImage(imageObj, kopce[json[z][7]], 200, 230, 230, lleft, ttop, 200, 200);
						var cl= "kopec-"+json[z][7];
					}
					if(z%11!=0 && z<110){
                                            var lx = (g%10);
                                            var ly = Math.floor(g/10);
                                            var l = (g%10)*100;
                                            var t = Math.floor(g/10)*100;
						if(json[z][10]!=0){
							
							var hranice = json[z][10];
							ctx.beginPath();
							if(json[z][8] == stat){
								ctx.strokeStyle = 'blue';
								ctx.lineWidth = 3;
							}else{
								ctx.strokeStyle = 'red';
								ctx.lineWidth = 3;
							}							
							if(hranice == 1){
								ctx.moveTo(l+97,t-3);
								ctx.lineTo(l+97,t+103);	
							}if(hranice == 2){
								ctx.moveTo(l+103,t+97);
								ctx.lineTo(l-3,t+97);	
							}if(hranice == 3){
								ctx.moveTo(l+97,t-3);
								ctx.lineTo(l+97,t+50);
								ctx.quadraticCurveTo(l+97, t+97, l+50, t+97);
								ctx.lineTo(l-3,t+97);	
							}if(hranice == 4){
								ctx.moveTo(l+3,t+103);
								ctx.lineTo(l+3,t-3);	
							}
							if(hranice == 5){
								ctx.moveTo(l+97,t-3);
								ctx.lineTo(l+97,t+103);	
								ctx.moveTo(l+3,t+103);
								ctx.lineTo(l+3,t-3);	
							}if(hranice == 6){
								ctx.moveTo(l+103,t+97);
								ctx.lineTo(l+50,t+97);	
								ctx.quadraticCurveTo(l, t+97, l+3, t+50);
								ctx.lineTo(l+3,t-3);	
							}if(hranice == 7){
								ctx.moveTo(l+97,t-3)
								ctx.lineTo(l+97,t+50);
								ctx.quadraticCurveTo(l+97, t+97, l+50, t+97);	
								ctx.quadraticCurveTo(l+3, t+97, l+3, t+50);
								ctx.lineTo(l+3,t-3);	
							}if(hranice == 8){
								ctx.moveTo(l-3,t+3);
								ctx.lineTo(l+103,t+3);	
							}if(hranice == 9){
								ctx.moveTo(l-3,t+3);
								ctx.lineTo(l+50,t+3);
								ctx.quadraticCurveTo(l+97, t+3, l+97, t+50);
								ctx.lineTo(l+97,t+103);									
							}if(hranice == 10){
								ctx.moveTo(l+103,t+97);
								ctx.lineTo(l-3,t+97);
								ctx.moveTo(l-3,t+3);
								ctx.lineTo(l+103,t+3);
							}if(hranice == 11){
								ctx.moveTo(l-3,t+3);
								ctx.lineTo(l+50,t+3);
								ctx.quadraticCurveTo(l+97, t+3, l+97, t+50);
								ctx.quadraticCurveTo(l+97, t+97, l+50, t+97);
								ctx.lineTo(l-3,t+97);	
							}if(hranice == 12){
								ctx.moveTo(l+3,t+103);
								ctx.lineTo(l+3,t+50);
								ctx.quadraticCurveTo(l+3, t+3, l+50, t+3);
								ctx.lineTo(l+103,t+3);	
							}if(hranice == 13){
								ctx.moveTo(l+3,t+103);
								ctx.lineTo(l+3,t+50);
								ctx.quadraticCurveTo(l+3, t+3, l+50, t+3);
								ctx.quadraticCurveTo(l+97, t+3, l+97, t+50);
								ctx.lineTo(l+97,t+103);									
							}if(hranice == 14){
								ctx.moveTo(l+103,t+97);
								ctx.lineTo(l+50,t+97);
								ctx.quadraticCurveTo(l+3, t+97, l+3, t+50);
								ctx.quadraticCurveTo(l+3, t+3, l+50, t+3);
								ctx.lineTo(l+103,t+3);	
							}if(hranice == 15){
								ctx.moveTo(l+50,t+3);
								ctx.quadraticCurveTo(l+97, t+3, l+97, t+50);
								ctx.quadraticCurveTo(l+97, t+97, l+50, t+97);
								ctx.quadraticCurveTo(l+3, t+97, l+3, t+50);
								ctx.quadraticCurveTo(l+3, t+3, l+50, t+3);
							}
						
						}
						ctx.stroke();
						poleinfo[json[z][0]][json[z][1]] = json[z];
						g++;
					}
					z = z+1;
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

	
	function mapa_clear(){ 
            for(var i in nacteno){
                if(nactenovi.indexOf(nacteno[i]) == -1){
                    $("#m"+nacteno[i].toString()).hide();
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


var xf,yf,f,nactenovix = [],nactenoviy = [];nactenovixi=0;nactenoviyi=0;
	function mapa_load(){
		//mapa_clear();
		var sirka = parseInt($("#back").css("width").replace("px",""));
		var vyska = parseInt($("#back").css("height").replace("px",""));

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
		nactenovi = [];
                nactenovix = [];
                nactenoviy = [];
                nactenovixi=0;
                nactenoviyi=0;
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
                                            $("#m"+x.toString()+"_"+y.toString()).show();
					}				
				}
				nactenovi.push(x.toString()+"_"+y.toString());
				mapa_poz(x,y);
				y++;
			}
			x++;
		}
                x = levo;
                while(x<=pravo){
                    nactenovix.push(x);
                    nactenovixi++;
                    x++;
		}
                y = dole;
		while(y<=nahore){
                    nactenoviy.push(y);
                    nactenoviyi++;
                    y++;
                }
		if(g){
			$.ajax({url: "index.php?post=mapa&x="+JSON.stringify(f), success: function(data){ 
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