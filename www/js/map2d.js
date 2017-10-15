function Mapa(map){
    this.map = map;
    
    this.move = false;
    this.startMove = {x:0,y:0};
    this.mapPosition = {x:0,y:0};
    this.sirka = 0;
    this.vyska = 0;
    this.velikost = 1000;
    this.nactenopoz = {x:[],y:[]};
    this.videnopoz = {x:[],y:[]};
    this.nacteno = [];
    this.videno = [];
    var t = this;
    
    
    this.init = function(x,y){
        t.pozices(x,y,0);
        this.map.tooltipInit($("#back"));
    };
    
    window.onresize = function(event) {
        t.mapload();
        t.load();
    };
    
    this.mapload = function(){
        this.sirka = parseInt($("#back").css("width"));
        this.vyska = parseInt($("#back").css("height"));
    };
    
    this.map.pozice = function(x,y){
        t.pozices(x,y,0);
    };
    
    this.map.obnovit = function(bloky){
        t.obnov(bloky);
    };
    
    this.map.renderCesta = function(pocatek,cesta){
        t.renderCesta(pocatek,cesta);
    };
    
    this.map.deleteCesta = function(pocatek,cesta){
        t.deleteCesta();
    };
    
    this.mapload();
    
    this.tahni = function(e){
        if(document.selection && document.selection.empty){
            document.selection.empty();
        }
        else if(window.getSelection)
        {
                var sel = window.getSelection();
                if(sel && sel.removeAllRanges) sel.removeAllRanges();
        }
        this.move = true;
        this.startMove.x = e.clientX;
        this.startMove.y = e.clientY;
        
        $("body").css( {'cursor': 'move','user-select': 'none' });
        var self = this;
        this.nacitani = setInterval(function(){self.load();},500);
        this.klik = true;
        this.kliktimer = setTimeout(function(){this.klik = false;},500);
    };
    
    this.pohyb = function(e){
        if(this.move){
            if(e.clientX-this.startMove.x > 5 || e.clientY-this.startMove.y > 5){
                this.klik = 0;
            }
            var rozdilX = e.clientX-this.startMove.x;
            var rozdilY = e.clientY-this.startMove.y;
            var x = this.mapPosition.x+rozdilX;
            var y = this.mapPosition.y+rozdilY;

            document.getElementById("move").style.left = x.toString()+"px";
            document.getElementById("pozxmove").style.left = x.toString()+"px";	
            document.getElementById("move").style.top = y.toString()+"px";
            document.getElementById("pozymove").style.top = y.toString()+"px";
        }else{                        
            var x = (e.clientX-this.mapPosition.x-250)*(1000/this.velikost);
            var y = (e.clientY-this.mapPosition.y-44)*(1000/this.velikost);
            x = Math.floor(x/100);
            y = -Math.floor(y/100)-1;
            var z = y*10+x;

            this.map.setTooltip(x,y);
        }
    };
    
    this.pust = function(e){
        this.move = false;
        this.mapPosition.x = parseInt($('#move').css("left"));
        this.mapPosition.y = parseInt($('#move').css("top"));
        clearTimeout(this.kliktimer);
        setTimeout(function(){this.klik = true;},50);
        $("body").css({'cursor': 'auto','user-select': 'text' } );
        clearInterval(this.nacitani);
        this.load();

        if(this.klik){
            var x = (e.clientX-this.mapPosition.x-250)*(1000/this.velikost);
            var y = (e.clientY-this.mapPosition.y-44)*(1000/this.velikost);
            x = Math.floor(x/100);
            y = -Math.floor(y/100)-1;
            var data = this.map.getPole(x,y);
            if(data[2] == 1){
                if(this.map.game.mesto.id == data[3]){
                    this.map.game.page_go("mesto");
                }else{
                    this.map.game.page_go("mestoinfo/"+data[3]);
                }
            } 
        }
        this.clear();
    };
    
    this.nacti = function(json,x,y){
        var left = x*100;
        var top = (-y-1)*100;
        $("#move").append("<div id='m"+x+"_"+y+"' class='mapblok' style='position: absolute;left: "+left.toString()+"%;top:"+top.toString()+"%' title=''></div>");
        this.vlozsvg(x,y,json);
        $("#m"+x+"_"+y)
            .css("background-image","url("+this.map.game.dir+"mapacache/"+x+"_"+y+"_"+json[100]+".jpg?")
            .waitForImages(function() {
                $(this).fadeOut(0)
                    .fadeIn(0);
            });
    };
    
    this.vlozsvg = function(x,y,json){
        var f = "";
        for(var z=0;z<100;z++){
            var l = (z%10)*100;
            var t = Math.floor(z/10)*100;
            if(json[z][5] == this.map.game.stat){
                var barva = "blue";
            }else{
                var barva = "red";
            }
            switch(parseInt(json[z][6])){
                case 1:
                    f += '<line x1="'+(l+97)+'" y1="'+(t-3)+'" x2="'+(l+97)+'" y2="'+(t+103)+'" style="stroke:'+barva+';stroke-width:3" class="sth'+json[z][8]+'" />';    
                    break;
                case 2:
                    f += '<line x1="'+(l-3)+'" y1="'+(t+97)+'" x2="'+(l+103)+'" y2="'+(t+97)+'" style="stroke:'+barva+';stroke-width:3" class="sth'+json[z][8]+'" />';    
                    break;
                case 3:
                    f += '<path d="M'+(l+97)+','+(t-3)+' q0,100 -100,100" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />';    
                    break;
                case 4:
                    f += '<line x1="'+(l+3)+'" y1="'+(t-3)+'" x2="'+(l+3)+'" y2="'+(t+103)+'" style="stroke:'+barva+';stroke-width:3" class="sth'+json[z][8]+'" />';    
                    break;
                case 5:
                    f += '<line x1="'+(l+97)+'" y1="'+(t-3)+'" x2="'+(l+97)+'" y2="'+(t+103)+'" style="stroke:'+barva+';stroke-width:3" class="sth'+json[z][8]+'" />';    
                    f += '<line x1="'+(l+3)+'" y1="'+(t-3)+'" x2="'+(l+3)+'" y2="'+(t+103)+'" style="stroke:'+barva+';stroke-width:3" class="sth'+json[z][8]+'" />';  
                    break;
                case 6:
                    f += '<path d="M'+(l+103)+','+(t+97)+' q-100,0 -100,-100" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />'; 
                    break;
                case 7:
                    f += '<path d="M'+(l+97)+','+(t-3)+' q0,100 -50,100 q-44,0 -44,-100" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />';  
                    break;
                case 8:
                    f += '<line x1="'+(l-3)+'" y1="'+(t+3)+'" x2="'+(l+103)+'" y2="'+(t+3)+'" style="stroke:'+barva+';stroke-width:3" class="sth'+json[z][8]+'" />'; 
                    break;
                case 9:
                    f += '<path d="M'+(l-3)+','+(t+3)+' q100,0 100,100" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />';    
                    break;
                case 10:
                    f += '<line x1="'+(l-3)+'" y1="'+(t+3)+'" x2="'+(l+103)+'" y2="'+(t+3)+'" style="stroke:'+barva+';stroke-width:3" class="sth'+json[z][8]+'" />';    
                    f += '<line x1="'+(l-3)+'" y1="'+(t+97)+'" x2="'+(l+103)+'" y2="'+(t+97)+'" style="stroke:'+barva+';stroke-width:3" class="sth'+json[z][8]+'" />'; 
                    break;
                case 11:
                    f += '<path d="M'+(l-3)+','+(t+3)+' q100,0 100,50 q0,44 -100,44" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />';   
                    break;
                case 12:
                    f += '<path d="M'+(l+3)+','+(t+103)+' q0,-100 100,-100" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />';   
                    break;
                case 13:
                    f += '<path d="M'+(l+3)+','+(t+103)+' q0,-100 50,-100 q44,0 44,100" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />';    
                    break;
                case 14:
                    f += '<path d="M'+(l+103)+','+(t+97)+' q-100,0 -100,-50 q0,-44 100,-44" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />'; 
                    break;
                case 15:
                    f += '<circle cx="'+(l+50)+'" cy="'+(t+50)+'" r="50" stroke="'+barva+'" stroke-width="3" fill="none" class="sth'+json[z][8]+'" />';    
                    break;
                default:
                    break;
            }
        }
        if(f != ""){
            $("#m"+x+"_"+y).html("<svg id='svg"+x+"_"+y+"' height='100%' width='100%' viewBox='0 0 1000 1000'>"+f+"</svg>");
        }else{
            $("#m"+x+"_"+y).html("");
        }
    };
    
    this.poz = function(x,y){
        if(this.nactenopoz.x.indexOf(x) == -1){
            this.nactenopoz.x.push(x);
            var left = x*100;
            $("#pozxmove").append("<div id='mx"+x+"' class='poz' style='position: absolute;left: "+left+"%'></div>")
            var z = x*10;
            while(z<(x+1)*10){
                    $("#mx"+x.toString()).append("<div>"+z+"</div>");
                    z = z+1;
            }
        }
        if(this.nactenopoz.y.indexOf(y) == -1){
            this.nactenopoz.y.push(y);
            var top = (-y-1)*100;
            $("#pozymove").append("<div id='my"+y.toString()+"' class='poz' style='position: absolute;top: "+top.toString()+"%'></div>")
            var z = (y+1)*10-1;
            while(z>y*10-1){
                    $("#my"+y.toString()).append("<div><span>"+z.toString()+"</span></div>");
                    z = z-1;
            }
        }
    };
    
    this.clear = function(){ 
        for(var i in this.nacteno){
            if(this.videno.indexOf(this.nacteno[i]) == -1){
                $("#m"+this.nacteno[i].toString()).remove();
            }
        }
        this.nacteno = this.videno;

        for(var i in this.nactenopoz.x){
            if(this.videnopoz.x.indexOf(this.nactenopoz.x[i]) == -1){
                $("#mx"+this.nactenopoz.x[i].toString()).remove();
            }
        }
        this.nactenopoz.x = this.videnopoz.x;

        for(var i in this.nactenopoz.y){
            if(this.videnopoz.y.indexOf(this.nactenopoz.y[i]) == -1){
                $("#my"+this.nactenopoz.y[i].toString()).remove();
            }
        }
        this.nactenopoz.y = this.videnopoz.y;
    };
    
    this.load = function(){
        var levo = -this.mapPosition.x;
        var pravo = levo+this.sirka;
        var nahore = this.mapPosition.y;
        var dole = nahore-this.vyska;
        levo = Math.floor(levo/this.velikost);
        pravo = Math.floor(pravo/this.velikost);
        nahore = Math.floor(nahore/this.velikost);
        dole = Math.floor(dole/this.velikost);
        var x = levo-1;
        var y;
        this.videno = [];
        var pozx = [];
        var pozy = [];

        var f = []; 
        var g = false;
        while(x<=pravo+1){
            y = dole-1;
            while(y<=nahore+1){
                if(x <= 19 && x >= -20 && y <= 19 && y >= -20){
                    if(this.nacteno.indexOf(x+"_"+y) == -1){
                        this.nacteno.push(x+"_"+y);
                        f.push(Array(x,y));			
                    }
                    this.videno.push(x+"_"+y);
                    this.poz(x,y);
                }
                y++;
            }
            x++;
        }
        x = levo-1;
        while(x<=pravo+1){
            pozx.push(x);
            x++;
        }
        y = dole-1;
        while(y<=nahore+1){
            pozy.push(y);
            y++;
        }
        this.videnopoz.x = pozx;
        this.videnopoz.y = pozy;
        if(f){
            var t = this;
            this.map.load(f,function(json,x,y){
                t.nacti(json,x,y);
            });
        }
    };
    
    this.obnov = function(bloky){
        var f = [];
        for(var i in bloky){
            if(this.videno.indexOf(bloky[i][0]+"_"+bloky[i][1]) != -1){
                f.push(bloky[i]);
            }
        }
        if(f){
            var t = this;
            this.map.load(f,function(json,x,y){
                t.nacti(json,x,y);
            });
        }
    };
    
    this.pozice = function(sx,sy){
        this.mapPosition.x = this.sirka/2-this.velikost/10*sx-this.velikost/10/2;
        this.mapPosition.y = this.vyska/2+this.velikost/10*sy+this.velikost/10/2+35;
        $("#move").animate({
                left: this.mapPosition.x,
                top: this.mapPosition.y
        }, 1000, function() {
                this.load();
        });
        $("#pozxmove").animate({
                left: this.mapPosition.x
        }, 1000);
        $("#pozymove").animate({
                top: this.mapPosition.y
        }, 1000);
        this.load();
    };

    this.pozices = function (sx,sy,sir){
        $("#move").fadeOut(0).fadeIn(1000);

        this.mapPosition.x = (this.sirka-sir)/2-this.velikost/10*sx-this.velikost/10/2;
        this.mapPosition.y = this.vyska/2+this.velikost/10*sy+this.velikost/10/2;
        document.getElementById("move").style.left = this.mapPosition.x.toString()+"px";
        document.getElementById("move").style.top = this.mapPosition.y.toString()+"px";
        document.getElementById("pozxmove").style.left = this.mapPosition.x.toString()+"px";
        document.getElementById("pozymove").style.top = this.mapPosition.y.toString()+"px";
        this.load();
    };

    this.zoom = function(na,zx,zy){
        $back = $("#back");
        var sirka = parseInt($back.css("width"));
        var vyska = parseInt($back.css("height"));
        this.mapPosition.x = this.mapPosition.x*(na/this.velikost)+(sirka/2)*(1-na/this.velikost)+zx*0.1;
        this.mapPosition.y = this.mapPosition.y*(na/this.velikost)+(vyska/2)*(1-na/this.velikost)+zy*0.1;
        this.velikost = na;
        $("#move").stop().animate({
            width: this.velikost,
            height: this.velikost,
            left: this.mapPosition.x,
            top: this.mapPosition.y
            }, 1);
        $("#pozxmove").stop().animate({
            width: this.velikost,
            left: this.mapPosition.x
            }, 1);
        $("#pozymove").stop().animate({
            height: this.velikost,
            top: this.mapPosition.y
        }, 1);
        this.load();
    };
    
    
    this.renderCesta = function(pocatek,cesta){
        var body = "";
        var ct = '<rect x="'+((pocatek[0]+200)*100)+'" y="'+((-pocatek[1]+189)*100)+'" width="100" height="100" style="fill:rgba(255,0,0,0.1);stroke-width:0;stroke:rgb(0,0,0)" />';
        for(var i in cesta){
            body += " "+(50+(cesta[i][0]+200)*100)+","+(-50+(-cesta[i][1]+190)*100);
            ct += '<rect x="'+((cesta[i][0]+200)*100)+'" y="'+((-cesta[i][1]+189)*100)+'" width="100" height="100" style="fill:rgba(255,0,0,0.1);stroke-width:0;stroke:rgb(0,0,0)" />';
        }
        
        $("#map_svg").html(ct+'<path d="M'+(50+(pocatek[0]+200)*100)+','+(-50+(-pocatek[1]+190)*100)+' '+body+'" stroke="red" stroke-width="3" fill="none" />');           
    };
    
    this.deleteCesta = function(){
        $("#map_svg").html('');           
    };
    
    
    $('#back').mousewheel(function (e) {
        if (e.deltaY > 0) {
            if (t.velikost < 1000) {
                var velikost = t.velikost + 50;
                t.zoom(velikost, 0, 0);
                $('.map_zoom').slider('value', velikost);
            }
        }
        else {
            if (t.velikost > 400) {  
                var velikost = t.velikost - 50;
                t.zoom(velikost, 0, 0);
                $('.map_zoom').slider('value', velikost);
            }
        }
    });

  
    $('.map_zoom').slider({
        min: 400,
        max: 1000,
        step: 50,
        value: this.velikost,
        slide: function (event, ui) {
            t.zoom(ui.value, 0, 0);
        },
        change: function (event, ui) {
            t.zoom(ui.value, 0, 0);
        }
    });
    $('.map_zoom_pop1').click(function () {
        t.zoom(400, 0, 0);
        $('.map_zoom').slider('value', 400);
    });
    
    $('.map_zoom_pop2').click(function () {
        t.zoom(600, 0, 0);
        $('.map_zoom').slider('value', 600);
    });
    
    $('.map_zoom_pop3').click(function () {
        t.zoom(1000, 0, 0);
        $('.map_zoom').slider('value', 1000);
    });
    
    $("#back").mousedown(function (event) {
        t.tahni(event, 1);
    });
    
    $("#back").mousemove(function (event) {
        t.pohyb(event);
    });
    
    $("#back").mouseup(function (e) {
        t.pust(e);
    });
    
    $("#back").on('touchstart', function (e) {
        e.preventDefault();
        var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
        t.tahni(touch, 0);
    });
    
    $("#back").on('touchmove', function (e) {
        e.preventDefault();
        var touches = e.originalEvent.touches || e.originalEvent.changedTouches;
        if(touches.lenght=1){
            t.pohyb(touches[0]);
        }
    });
    
    $("#back").on('touchend', function (e) {
        t.pust(e);
    });	
}