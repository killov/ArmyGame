
function Game(map){
    this.map = map;
    this.mesto = {
        x: 0,
        y: 0
    };
    
    this.url = "";
    this.map = false;
    this.time_rozdil();
    
    this.cesta = function(x){
        $.ajax({url: dir+"index.php?post=cesta&id="+x, success: function(result){
            $("#cont").show()
                    .html(result);
            $("#jed").hide();
            var cesta = JSON.parse(result);
            this.map.renderCesta([mesto_x,mesto_y], cesta);
        }});
    };
    
    
};