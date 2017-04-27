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
var faq = 0;
var velikost = 1000;
var poleinfo = [];
for(var x = -200;x<200;x++){
    poleinfo[x] = [];
    for(var y = -200;y<200;y++){
        poleinfo[x][y] = false;
    }
}
       




function ws_connect(){


    websocket = new WebSocket(wsUri); 
    websocket.onmessage = get;

    websocket.onerror	= function(ev){
        console.log("Error Occurred - "+ev.data);

    }; 
    websocket.onclose 	= function(ev){
        console.log("Connection Closed");
        setTimeout("ws_connect()",1000);
    }; 
    websocket.onopen = function(ev) { // connection is open 
        console.log("Connected!"); //notify user
        $.ajax({url: dir+"index.php?post=ws", success: function(result){
            var res = JSON.parse(result)
            send({typ: "auth", hash: res[0]});
        }});
    }
}
    
function get(ev) {

        msg = JSON.parse(ev.data);
        console.log(msg);

        if(msg.typ == "mapa_refresh"){
            mapa_obnov(msg.bloky);
        }else if(msg.typ == "chatme"){
            pridejdochatu(msg.pro,"my",msg.text,msg.time);
        }
        else if(msg.typ == "chat"){
            pridejdochatu(msg.od,"vy",msg.text,msg.time);
        }
    }

function send(data){

     websocket.send(JSON.stringify(data));

}
    

    
    
 (function ($) {
    $.fn.wysiwygEvt = function () {
        return this.each(function () {
            var $this = $(this);
            var htmlold = $this.html();
            $this.bind('blur keyup paste copy cut mouseup', function () {
                var htmlnew = $this.html();
                if (htmlold !== htmlnew) {
                    $this.trigger('change')
                }
            })
        })
    }
})(jQuery);

function pridejdochatu(chat,typ,zprava,time){
    otevrichat(chat,true);
    $("#mess"+chat).append("<div class='"+typ+"' title='"+time+"'>"+zprava+"</div>");
    $("#mess"+chat).scrollTop(999999999);
}



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
        var h,m,s;
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
    return h+":"+m+":"+s;
}

function formular_upload(form,kam,callback){
    $(form).submit(function(ev) {
	$.post(dir+kam, $(this).serialize(), function(data){ 
    var json = eval("(" + data + ")");
	callback(json);
	});
        ev.preventDefault();
    });
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
    };
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
        
        
        
    };
};

