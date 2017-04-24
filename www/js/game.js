
function Game(map){
    this.map = map;
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
    
    this.dir = "";
    this.url = "";
    this.time_rozdil = 0;
    this.map = false;
    this.faq = false;
    this.time_rozdil();
    this.rtime;
    this.faq_load = function(x){
        if(!this.faq){
            $("#faq").fadeIn(500);
            this.faq = true;
        }
        $("#faq_obsah").load(dir+"index.php?faq="+x);
    };   

    this.faq_close = function(){
        $("#faq").fadeOut(500);
        this.faq = false;
    };
    
    this.page_load = function(x){
        this.url = x;
        this.data = "";
        this.load = 0;
        $.ajax({url: dir+"index.php?p="+x+"&a", success: function(result){
            this.data = result;
            if(this.load == 2){
                window.history.pushState({}, "Armygame", dir+url);
                $("#obsah_h").fadeOut(0);
                $("#obsah_h").html(data);
                this.setlinks();
                $("#obsah_h").fadeIn(0);
                this.hidemap();
            }else{
                this.load = 1;
            }
        }});
    };

    this.page_draw = function(){
        if(this.load == 1){
            window.history.pushState({}, "Armygame", dir+url);
            $("#obsah_h").fadeOut(0);
            $("#obsah_h").html(data);
            this.setlinks();
            $("#obsah_h").fadeIn(0);
            this.hidemap();
        }else{
            this.load = 2;
        }
    };

    this.page_refresh = function(){
        $.ajax({url: dir+"index.php?p="+url+"&a", success: function(result){
            $("#obsah_h").html(result);
            this.setlinks();
        }});
    };

    
    this.page_go = function(x){
        window.history.pushState({}, "Armygame", this.dir+x);
	this.page_gog(x);
    };
    
    this.page_gog = function(x){
	this.url = x;
	$.ajax({url: dir+"index.php?p="+x+"&a", success: function(result){
            $("#obsah_h").hide();
            $("#obsah_h").html(result);
            this.setlinks();
            $("#obsah_h").fadeIn(0);
            this.hidemap();    
	}});
    };
    
    this.setlinks = function(){
        $(".link").click(function(e){
            e.preventDefault();
        })
        .mousedown(function(e){
            e.preventDefault();
            this.page_load($(this).attr("h"));
        }).mouseup(function(e){
            e.preventDefault();
            this.page_draw();
        });
    };
    
    this.data_load = function(){
        clearTimeout(this.rtime);
        $.ajax({url: this.dir+"index.php?post=mestodata", success: function(data){ 
            var json = eval("(" + data + ")");
            var penize = json["penize"];
            this.mesto.surovina1 = json["surovina1"];
            this.mesto.surovina2 = json["surovina2"];
            this.mesto.surovina3 = json["surovina3"];
            this.mesto.surovina4 = json["surovina4"];
            this.mesto.surovina1_p = json["surovina1_produkce"];
            this.mesto.surovina2_p = json["surovina2_produkce"];
            this.mesto.surovina3_p = json["surovina3_produkce"];
            this.mesto.surovina4_p = json["surovina4_produkce"];
            this.mesto.sklad = json["sklad"];
            $("#surovina0").text(penize.toString());
            $("#surovina1").text(this.mesto.surovina1.toString());
            $("#surovina2").text(this.mesto.surovina2.toString());
            $("#surovina3").text(this.mesto.surovina3.toString());
            $("#surovina4").text(this.mesto.surovina4.toString());
            $("#surovina1_p").text(this.mesto.surovina1_p.toString());
            $("#surovina2_p").text(this.mesto.surovina2_p.toString());
            $("#surovina3_p").text(this.mesto.surovina3_p.toString());
            $("#surovina4_p").text(this.mesto.surovina4_p.toString());
            $("#sklad").text(this.mesto.sklad.toString());
            $("#jednotky").html(json["jednotky"]);
            this.rtime = setTimeout("data_load()", json["refresh"]*1000);
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
    
    
    this.cesta = function(x){
        $.ajax({url: dir+"index.php?post=cesta&id="+x, success: function(result){
            $("#cont").show()
                    .html(result);
            $("#jed").hide();
            var cesta = JSON.parse(result);
            this.map.renderCesta([this.mesto.x,this.mesto.y], cesta);
        }});
    };
    
    
};