
function Game(){
    var self = this;
    this.mesto = {
        x: 0,
        y: 0,
        surovina1: 0,
        surovina2: 0,
        surovina3: 0,
        surovina4: 0,
        surovina1_p: 0,
        surovina2_p: 0,
        surovina3_p: 0,
        surovina4_p: 0,
        sklad: 0
    };
    
    this.stat = 0;    
    this.dir = "";
    this.url = "";
    this.time_rozdil = 0;
    this.map = false;
    this.faq = false;
    this.wsUri = "";
    this.ws;
    this.rtime;
    this.faq_load = function(x){
        if(!this.faq){
            $("#faq").fadeIn(500);
            this.faq = true;
        }
        $("#faq_obsah").load(this.dir+"index.php?faq="+x);
    };   
    
    this.init = function(){
        var self = this;
        window.onpopstate = function(e) {
            self.page_gog(location.pathname.replace(self.dir,"")? location.pathname.replace(self.dir,"") : "mesto");
        };    
        $( "#faq" ).draggable({ 
            handle: "h2",
            cancel: "i.close"
        });
        this.ws_connect();
        this.mapControl = new Map(this);
        this.chat = new Chat(this);
        this.data_load();
    };
    
    setInterval(function(){
        self.timeloop();
    }, 500);
    
    this.timeloop = function(){
        var d = new Date();
        var time = this.time_rozdil+d.getTime();
        this.timelooppage(time);
        this.produkce();
    };
    
    this.timelooppage = function(time){
        
    };
    
    this.ws_connect = function(){
        var self = this;
        this.ws = new WebSocket(this.wsUri); 
        this.ws.onmessage = function(ev) {
            msg = JSON.parse(ev.data);
            console.log(msg);
            if(msg.typ == "mapa_refresh"){
                self.mapControl.obnov(msg.bloky);
            }else if(msg.typ == "chatme"){
                self.chat.pridej(msg.pro,"my",msg.text,msg.time);
            }
            else if(msg.typ == "chat"){
                self.chat.pridej(msg.od,"vy",msg.text,msg.time);
            }
        };
        
        this.ws.onerror = function(ev){
            console.log("Error Occurred - "+ev.data);
        }; 
        
        this.ws.onclose = function(ev){
            console.log("Connection Closed");
            setTimeout(function(){self.ws_connect();},1000);
        }; 
        
        this.ws.onopen = function(ev) { // connection is open 
            console.log("Connected!"); //notify user
            $.ajax({url: self.dir+"index.php?post=ws", success: function(result){
                var res = JSON.parse(result);
                self.ws_send({typ: "auth", hash: res[0]});
            }});
        };
    };
    
    this.ws_send = function(data){
        this.ws.send(JSON.stringify(data));
    };
    
    this.hlaska = function(x,typ){
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
    };

    this.faq_close = function(){
        $("#faq").fadeOut(500);
        this.faq = false;
    };
    
    this.page_load = function(x){
        this.data = "";
        this.load = 0;
        var self = this;
        $.ajax({url: this.dir+"index.php?p="+x+"&a", success: function(result){
            self.data = result;
            self.url = x;
            if(self.load == 2){
                this.timelooppage = function(){};
                window.history.pushState({}, "Armygame", self.dir+x);
                $("#obsah_h").fadeOut(0);
                $("#obsah_h").html(self.data);
                self.setlinks();
                $("#obsah_h").fadeIn(0);
                self.hidemap();
            }else{
                self.load = 1;
            }
        }});
    };

    this.page_draw = function(){
        if(this.load == 1){
            this.timelooppage = function(){};
            window.history.pushState({}, "Armygame", this.dir+this.url);
            $("#obsah_h").fadeOut(0);
            $("#obsah_h").html(this.data);
            this.setlinks();
            $("#obsah_h").fadeIn(0);
            this.hidemap();
        }else{
            this.load = 2;
        }
    };

    this.page_refresh = function(){
        this.timelooppage = function(){};
        var self = this;
        $.ajax({url: this.dir+"index.php?p="+this.url+"&a", success: function(result){
            $("#obsah_h").html(result);
            self.setlinks();
        }});
    };

    
    this.page_go = function(x){
        window.history.pushState({}, "Armygame", this.dir+x);
	this.page_gog(x);
    };
    
    this.page_gog = function(x){
        this.timelooppage = function(){};
	this.url = x;
        var self = this;
	$.ajax({url: this.dir+"index.php?p="+x+"&a", success: function(result){
            $("#obsah_h").hide();
            $("#obsah_h").html(result);
            self.setlinks();
            $("#obsah_h").fadeIn(0);
            self.hidemap();    
	}});
    };
    
    this.setlinks = function(){
        var self = this;
        $(".link").click(function(e){
            e.preventDefault();
        })
        .mousedown(function(e){
            e.preventDefault();
            self.page_load($(this).attr("h"));
        }).mouseup(function(e){
            e.preventDefault();
            self.page_draw();
        });
    };
    
    this.data_load = function(){
        clearTimeout(this.rtime);
        var self = this;
        $.ajax({url: this.dir+"index.php?post=mestodata", success: function(data){ 
            var json = eval("(" + data + ")");
            var penize = json["penize"];
            self.mesto.surovina1 = json["surovina1"];
            self.mesto.surovina2 = json["surovina2"];
            self.mesto.surovina3 = json["surovina3"];
            self.mesto.surovina4 = json["surovina4"];
            self.mesto.surovina1_p = json["surovina1_produkce"];
            self.mesto.surovina2_p = json["surovina2_produkce"];
            self.mesto.surovina3_p = json["surovina3_produkce"];
            self.mesto.surovina4_p = json["surovina4_produkce"];
            self.mesto.sklad = json["sklad"];
            $("#surovina0").text(penize.toString());
            $("#surovina1").text(self.mesto.surovina1.toString());
            $("#surovina2").text(self.mesto.surovina2.toString());
            $("#surovina3").text(self.mesto.surovina3.toString());
            $("#surovina4").text(self.mesto.surovina4.toString());
            $("#surovina1_p").text(self.mesto.surovina1_p.toString());
            $("#surovina2_p").text(self.mesto.surovina2_p.toString());
            $("#surovina3_p").text(self.mesto.surovina3_p.toString());
            $("#surovina4_p").text(self.mesto.surovina4_p.toString());
            $("#sklad").text(self.mesto.sklad.toString());
            $("#jednotky").html(json["jednotky"]);
            self.rtime = setTimeout(function(){self.data_load();}, json["refresh"]*1000);
        }});
    };
    
    this.produkce = function(){
        this.mesto.surovina1 = this.mesto.surovina1+this.mesto.surovina1_p/3600;
        if(this.mesto.surovina1>this.mesto.sklad){
            this.mesto.surovina1 = this.mesto.sklad;
        }
        this.mesto.surovina2 = this.mesto.surovina2+this.mesto.surovina2_p/3600;			
        if(this.mesto.surovina2>this.mesto.sklad){
            this.mesto.surovina2 = this.mesto.sklad;
        }
        this.mesto.surovina3 = this.mesto.surovina3+this.mesto.surovina3_p/3600;
        if(this.mesto.surovina3>this.mesto.sklad){
            this.mesto.surovina3 = this.mesto.sklad;
        }
        this.mesto.surovina4 = this.mesto.surovina4+this.mesto.surovina4_p/3600;
        if(this.mesto.surovina4>this.mesto.sklad){
            this.mesto.surovina4 = this.mesto.sklad;
        }
        if(this.mesto.surovina4<0){
            this.data_load();
        }else{
            $("#surovina1").text(Math.floor(this.mesto.surovina1).toString());
            $("#surovina2").text(Math.floor(this.mesto.surovina2).toString());
            $("#surovina3").text(Math.floor(this.mesto.surovina3).toString());
            $("#surovina4").text(Math.floor(this.mesto.surovina4).toString());
        }
    };    
    
    this.jednotky_poslat = function(x){
        $.ajax({url: this.dir+"index.php?post=jednotky_poslat&id="+x, success: function(result){
            $("#cont").show()
                    .html(result);
            $("#jed").hide();
        }});
    };

    this.postav = function(x){
        var self = this;
        $.post(this.dir+"index.php?post=postav",{bid: x},function(){
            self.data_load();
            self.page_refresh();
        });
    };

    this.vyzkum = function(x){
        var self = this;
        $.post(this.dir+"index.php?post=vyzkum",{vid: x},function(){
            self.data_load();
            self.page_refresh();
        });
    };

    this.jednotky_vyzkum = function(x){
        var self = this;
        $.post(this.dir+"index.php?post=jednotky_vyzkum",{jid: x},function(){
            self.data_load();
            self.page_refresh();
        });
    };

    this.pozvankazrusit = function(x){
        var self = this;
        $.post(this.dir+"index.php?post=statsmazatpozvanku",{id: x},function(){
            self.page_refresh();
        });
    };

    this.pozvankapotvrdit = function(x){
        var self = this;
        $.post(this.dir+"index.php?post=statpotvrditpozvanku",{id: x},function(data){
            var d = JSON.parse(data);
            self.stat = d[0];
            self.page_refresh();
        });
    };
    
    this.opustitstat = function(){
        var self = this;
        $.post(this.dir+"index.php?post=opustitstat",function(){
            self.stat = 0;
            self.page_go("stat");
        });
    };
    
    this.mapa = function(){
        if(this.map){
            this.mapControl.pozice(this.mesto.x,this.mesto.y);
        }else{
            this.showmap();
        }
    };

    this.showmap = function(){
        $("#celek").fadeOut(500);
        this.map = true;
    };

    this.hidemap = function(){
        if(this.map){
            $("#celek").fadeIn(500);
            this.map = false;
        }
    };	


    this.jednotky = function(id,s1,s2,s3,s4,cass,spotreba){
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
    };
    
    this.formular_upload = function(form,kam,callback){
        var self = this;
        $(form).submit(function(ev){
            $.post(self.dir+kam, $(this).serialize(), function(data){ 
                var json = eval("(" + data + ")");
                callback(json);
            });
            ev.preventDefault();
        });
    };
    
    this.cesta = function(x){
        var self = this;
        $.ajax({url: game.dir+"index.php?post=cesta&id="+x, success: function(result){
            $("#cont").show()
                    .html(result);
            $("#jed").hide();
            var cesta = JSON.parse(result);
            self.mapControl.renderCesta([self.mesto.x,self.mesto.y], cesta);
        }});
    };
    
    
};