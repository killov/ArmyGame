function Map(game){
    this.game = game;
    this.cache = [];
    this.pole = [];
    this.staty = [];
    for(i=-20;i<20;i++) this.cache[i] = [];
    for(i=-200;i<199;i++) this.pole[i] = [];

    this.load = function(bloky, callback){
        var t = this;
        var f = [];
        for(var i in bloky){
            if(t.cache[bloky[i][0]][bloky[i][1]]){
                callback(t.cache[bloky[i][0]][bloky[i][1]],bloky[i][0],bloky[i][1]);
            }else{
                f.push(bloky[i]);
            }
        }
        if(f.length){
            $.ajax({url: this.game.dir+"index.php?post=mapa&x="+JSON.stringify(f), success: function(data){ 
                var js = JSON.parse(data);
                for(var i in js[1]){
                    t.staty[i] = js[1][i];
                }
                var json = js[0];
                for(var x in json){
                    for(var y in json[x]){
                        t.cache[x][y] = json[x][y];
                        callback(json[x][y],x,y);
                        for(var z = 0;z<100;z++){
                            t.pole[json[x][y][z][0]][json[x][y][z][1]] = json[x][y][z];
                        }
                    }
                }				
            }});
        }
    };
    
    this.getPole = function(x,y){
        return this.pole[x][y];
    };
    
    this.getStat = function(id){
        return this.staty[id];
    };
    
    this.obnov = function(bloky){
        for(var i in bloky){
            this.cache[bloky[i][0]][bloky[i][1]] = false;
        }
        this.obnovit(bloky);
    };
    
    this.pozice = function(){
        console.log("map.pozice callback is missing");
    };
    
    this.obnovit = function(){
        console.log("map.obnovit callback is missing");
    };
    
    this.renderCesta = function(){
        console.log("map.renderCesta callback is missing");
    };
}
