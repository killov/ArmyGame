function Chat(game){
    this.game = game;
    
    this.chatr = [];
    this.chatl = [];
    this.chatm = [];
    this.schovany = [];
    
   
	
       
    
    this.otevri = function(x,max){
        var self = this;
        if(this.chatr.indexOf(x) == -1){
            this.chatr.push(x);
            cookies.set('ag_chat', JSON.stringify(this.chatr));
            $.post(this.game.dir+"index.php?post=chat",{id: x},function(data){
                var d = JSON.parse(data);
                self.chatl[x] = d[2];
                if(max){
                    self.schovany[x] = false;
                    var m = "-300";
                }else{
                    self.schovany[x] = true;
                    var m = "-25";

                }
                $("#chat").append("<div id='chat"+x+"' style='top: "+m+"px'><div class='head'><a href='#' onclick='return false' class='prof' h='profil/"+x+"'>"+d[0]+"</a><i class='icon-cross close'></i> <i class='icon-triangle-down min'></i></div><div class='messages' id='mess"+x+"'><div>"+d[1]+"</div></div><div class='textbox' id='t"+x+"' placeholder='sad' contenteditable='true' pro='"+x+"'></div></div>");
                if(!max){
                    $("#chat"+x+">div.head>i.min").removeClass("icon-triangle-down").addClass("icon-triangle-up");
                }
                $("#chat"+x+">div.head>a.prof").mousedown(function(e){
                    e.preventDefault();
                    self.game.page_load($(this).attr("h"));
                }).mouseup(function(e){
                    e.preventDefault();
                    self.game.page_draw();
                });
                document.getElementById('t'+x).focus();
                $("#chat"+x+">div.head>i.close").click(function(e){
                    e.preventDefault();
                    self.zavrit(x);
                });

                $("#chat"+x+">div.head>i.min").click(function(e){
                    e.preventDefault();
                    self.schovat(x);
                });

                $("#chat"+x+" .textbox").wysiwygEvt();
                $("#chat"+x+" .messages").scrollTop(9999999999999).scroll(function(e){
                    if(self.chatl[x] >= 0 && $(this).scrollTop() == 0){
                        self.nacist(x);
                    }
                });
                $("#chat"+x+" .textbox").on('change keypress delete',function(e) {
                    if(e.which == 13) {
                        self.poslat(parseInt($(this).attr("pro")),$(this).text());
                        $(this).text("");
                        e.preventDefault();
                    }
                    $(this).parent().children(".messages").css("height",258-parseInt($(this).css("height")));
                    $("#chat"+x+" .messages").scrollTop(9999999999999);
                });
            });
        }else if(this.chatm.indexOf(x) != -1){
            this.schovat(x);
        }
    };

    var c = cookies.get('ag_chat');
    this.chatm = cookies.get('ag_chatmin');
    if(!this.chatm){
        this.chatm = [];
    }
    for(var i in c){
        if(this.chatm && this.chatm.indexOf(c[i]) != -1){
            this.otevri(c[i],false);
        }else{
            this.otevri(c[i],true);
        }
    }
    this.nacist = function(chat){
        var self = this;
        $.post(this.game.dir+"index.php?post=chat",{id: chat, od: this.chatl[chat]},function(data){            
            var d = JSON.parse(data);
            self.chatl[chat] = d[2];
            var lastelm = $("#mess"+chat+" div:first");
            var top = lastelm.offset().top;
            $("#mess"+chat).prepend("<div>"+d[1]+"</div>");
            top = lastelm.offset().top-top;
            $("#mess"+chat).scrollTop(top);
        });
    };
        
    this.schovat = function(chat){
        if(this.schovany[chat]){
            this.chatm.splice(this.chatm.indexOf(chat),1);
            $("#chat"+chat).animate({top: "-300px"},300);
            this.schovany[chat] = false;
            $("#chat"+chat+">div.head>i.min").removeClass("icon-triangle-up").addClass("icon-triangle-down");
        }else{
            this.chatm.push(chat);
            $("#chat"+chat).animate({top: "-25px"},300);
            this.schovany[chat] = true;
            $("#chat"+chat+">div.head>i.min").removeClass("icon-triangle-down").addClass("icon-triangle-up");
        }
        cookies.set('ag_chatmin',JSON.stringify(this.chatm));
    };
    
    this.zavrit = function(x){
        $("#chat"+x).remove();
        this.chatr.splice(this.chatr.indexOf(x),1);
        cookies.set('ag_chat', JSON.stringify(this.chatr));
    };
    
    this.poslat = function(pro,text){
        this.game.ws_send({
           typ: "chat",
           pro: pro,
           text: text
        });
    };
    
    this.pridej = function(chat,typ,zprava,time){
        this.otevri(chat,true);
        $("#mess"+chat).append("<div class='"+typ+"' title='"+time+"'>"+zprava+"</div>");
        $("#mess"+chat).scrollTop(999999999);
    };
}
