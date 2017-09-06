function login_load(x){
	$("#obsah_h").load("index.php?p="+x,function(data){
		$("#obsah_h").html(data);
	});
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


function cas(time){
    var h,m,s;
    h = Math.floor(time/3600)%24;
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
	$.post(kam, $(this).serialize(), function(data){ 
    var json = eval("(" + data + ")");
	callback(json);
	});
        ev.preventDefault();
    });
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