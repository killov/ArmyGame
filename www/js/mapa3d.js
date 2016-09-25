function mapa_load(bloky,callback){
    $.ajax({url: dir+"index.php?post=mapa&x="+JSON.stringify(bloky), success: function(data){ 
        var json = JSON.parse(data);
        for(var x in json){
            for(var y in json[x]){
                callback(json[x][y],x,y);
                for(var z = 0;z<100;z++){
                    poleinfo[json[x][y][z][0]][json[x][y][z][1]] = json[x][y][z];
                }
            }
        }				
    }});
}

function mapa_obnov(bloky){
    
}


