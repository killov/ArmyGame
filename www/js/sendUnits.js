function SendUnits(game){
    this.game = game;
    
    this.data = {};
    this.target = {};
    this.time = -1;
    
    var self = this;
    
    this.rdy = false;
    this.podpory = null;
    this.source = 0;
    
    this.start = function(target){
        $("#cont").show();
        $("#jed").hide();
        this.source = 0;
        this.getData(target);
        this.getPodpory();     
        this.resetUnits();
        this.rdy = false;
    };
    
    this.getData = function(target){
        var self = this;
        $.ajax({url: this.game.dir+"index.php?post=cesta&id="+target+"&source="+this.source, success: function(result){
            var data = JSON.parse(result);
            self.target = data.city;
            if(self.source == 0){
                self.game.mapControl.renderCesta([self.game.mesto.x,self.game.mesto.y], data.cesta);
            }else{
                self.game.mapControl.renderCesta([self.podpory[self.source].x,self.podpory[self.source].y], data.cesta);
            }
            self.drawTarget();
            if(!self.rdy){
                self.updateSource();
                self.rdy = true;
            }
        }});
    };
    
    this.drawTarget = function(){
        var self = this;
        var city = $("<a href='#'>"+this.target.jmeno+"</a>")
            .click(function(){
                self.game.page_go("mestoinfo/"+self.target.id);
                return false;
            });
        var user = $("<a href='#'>"+this.target.userjmeno+"</a>")
            .click(function(){
                self.game.page_go("profil/"+self.target.user);
                return false;
            });
        var stat = $("<a href='#'>"+this.target.statjmeno+"</a>")
            .click(function(){
                self.game.page_go("stat&id="+self.target.stat);
                return false;
            });
        $("#su_city").html(city);
        $("#su_user").html(user);
        $("#su_stat").html(stat);
        $("#su_distance").html(this.target.distance/10);
    };
    
    this.getPodpory = function(){
        
        var self = this;
        $.ajax({url: this.game.dir+"index.php?post=podpory", success: function(result){
            var res = JSON.parse(result);
            self.podpory = res;
            self.updateSource(res);
        }});
    };
    
    this.updateSource = function(){
        if(this.podpory){
            var c = "<option value='0'>"+this.game.mesto.jmeno+" ("+this.game.mesto.x+"|"+this.game.mesto.y+")</option>";
            for(var i in this.podpory){
                if(this.target.id != this.podpory[i].kde){
                     c += "<option value='"+i+"'>"+this.podpory[i].jmeno+" ("+this.podpory[i].x+"|"+this.podpory[i].y+")</option>";
                }
            }
            $("#su_source").html(c);
            
        }     
    };
    
    $("#su_source").change(function(){
        self.source = $(this).val();
        self.resetUnits();
        self.getData(self.target.id);
    });
    
    this.recalculate = function(){
        var nosnostPechoty = 0,
            infantry = 0;
            slowestUnit = 0,
            slowestVehicle = 0,
            nosnost = 0,
            unit = false;
        for(var i in this.data){
            var info = this.data[i];
            var count = parseInt($("#pj"+i).val());
            count = isNaN(count) ? 0 : count;
            if(count > 0){
                nosnost += info.nosnost*count;
                if(i < 5){
                    infantry += count;
                    if(slowestUnit < info.rychlost){
                        slowestUnit = info.rychlost;
                    }
                }else{
                    nosnostPechoty += info.nosnost_pechoty*count;
                    if(slowestVehicle < info.rychlost){
                        slowestVehicle = info.rychlost;
                    }
                }
                unit = true;
            }
        }
        $("#su_capacity").html(infantry+"/"+nosnostPechoty);
        if(unit){
            var speed = (nosnostPechoty >= infantry) ? slowestVehicle : slowestUnit;
            this.time = speed*60*this.target.distance/10;
            $("#su_time").html(cas(this.time));
        }else{
            this.time = -1;
            this.timer();
            $("#su_time").html("");
        }
        var surovin = 0;
        for(var i = 1; i<=4;i++){
            var count = parseInt($("#su_sur"+i).val());
            count = isNaN(count) ? 0 : count;
            if(count > 0){
                surovin += count;
            }
        }
        $("#su_nosnost").html(surovin+"/"+nosnost);
    };
    
    this.timer = function(time){
        if(this.time > -1){
            $("#su_coming_time").html(cas(Math.round(time/1000)+this.time+this.game.timeZone));
        }else{
            $("#su_coming_time").html("");
        }
    };
    
    this.resetUnits = function(){
        for(var i = 1;i<=8;i++){
            $("#pj"+i).val("");
            if(this.source == 0){
                $("#pjk"+i).text(this.game.mesto.jednotky[i].toString());
            }else{
                $("#pjk"+i).text(this.podpory[this.source].j[i].toString());
            }
        }
        this.recalculate();
    };
    
    this.cancel = function(){
        $("#cont").hide();
        $("#jed").show();
        this.game.mapControl.deleteCesta();
    };
    
    this.send = function(){
        var self = this;
        $.post(this.game.dir+"index.php?post=sendUnits", {
            j1: $("#pj1").val(),
            j2: $("#pj2").val(),
            j3: $("#pj3").val(),
            j4: $("#pj4").val(),
            j5: $("#pj5").val(),
            j6: $("#pj6").val(),
            j7: $("#pj7").val(),
            j8: $("#pj8").val(),
            surovina1: $("#su_sur1").val(),
            surovina2: $("#su_sur2").val(),
            surovina3: $("#su_sur3").val(),
            surovina4: $("#su_sur4").val(),
            target: this.target.id,
            source: this.source
        }, function(data){ 
            var json = JSON.parse(data);
            if(json[0] == 0){
                self.cancel();
                self.game.data_load();
                self.rdy = false;
            }
        });
    };
    
    for(var i = 1;i<=8;i++){
        $("#pj"+i).keyup(function(){
            self.recalculate();
        });
    }
    
    for(var i = 1;i<=4;i++){
        $("#su_sur"+i).keyup(function(){
            self.recalculate();
        });
    }
    
    $("#su_form").submit(function(){
        self.send();
        return false;
    });
    
    
    
    $(".pjk").click(function(){
        var j = $(this).attr("j");
        var input = $("#pj"+j);
        if(self.source == 0){
            var jed = self.game.mesto.jednotky[j];
        }else{
            var jed = self.podpory[self.source].j[j];
        }
        if(parseInt(input.val()) == jed){
            input.val("");
        }else{
            input.val(jed.toString());
        }
        self.recalculate();
        return false;
    });
}
