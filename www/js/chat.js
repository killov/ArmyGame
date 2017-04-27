function Chat(game){
    this.game = game;
    
    this.chatr = [];
    this.chatl = [];
    this.chatm = [];
    
    this.otevrichat = function(x,max){
    if(this.chatr.indexOf(x) == -1){
        this.chatr.push(x);
        cookies.set('ag_chat', JSON.stringify(chatr));

        $.post(this.game.dir+"index.php?post=chat",{id: x},function(data){
            var d = JSON.parse(data);
            chatl[x] = d[2];
            if(max){
                schovany[x] = false;
                var m = "-300";
            }else{
                schovany[x] = true;
                var m = "-25";

            }
            $("#chat").append("<div id='chat"+x+"' style='top: "+m+"px'><div class='head'><a href='#' onclick='return false' class='prof' h='profil/"+x+"'>"+d[0]+"</a><i class='icon-cross close'></i> <i class='icon-triangle-down min'></i></div><div class='messages' id='mess"+x+"'><div>"+d[1]+"</div></div><div class='textbox' id='t"+x+"' placeholder='sad' contenteditable='true' pro='"+x+"'></div></div>");
            if(!max){
                $("#chat"+x+">div.head>i.min").removeClass("icon-triangle-down").addClass("icon-triangle-up");
            }
            $("#chat"+x+">div.head>a.prof").mousedown(function(e){
                e.preventDefault();
                page_load($(this).attr("h"));
            }).mouseup(function(e){
                e.preventDefault();
                page_draw();
            });
            document.getElementById('t'+x).focus();
            $("#chat"+x+">div.head>i.close").click(function(e){
                e.preventDefault();
                zavritchat(x);
            });

            $("#chat"+x+">div.head>i.min").click(function(e){
                e.preventDefault();
                schovatchat(x);
            });

            $("#chat"+x+" .textbox").wysiwygEvt();
            $("#chat"+x+" .messages").scrollTop(9999999999999).scroll(function(e){
                if(chatl[x] >= 0 && $(this).scrollTop() == 0){
                    nacistchat(x);
                }
            });
            $("#chat"+x+" .textbox").on('change keypress delete',function(e) {
                if(e.which == 13) {
                    chat_poslat(parseInt($(this).attr("pro")),$(this).text());
                    $(this).text("");
                    e.preventDefault();
                }
                $(this).parent().children(".messages").css("height",258-parseInt($(this).css("height")));
                $("#chat"+x+" .messages").scrollTop(9999999999999);
            });
        });
    }else if(chatm.indexOf(x) != -1){
        schovatchat(x);
    }
}
    
    function nacistchat(chat){
        $.post(dir+"index.php?post=chat",{id: chat, od: chatl[chat]},function(data){            
            var d = JSON.parse(data);
            chatl[chat] = d[2];
            var lastelm = $("#mess"+chat+" div:first");
            var top = lastelm.offset().top;
            $("#mess"+chat).prepend("<div>"+d[1]+"</div>");
            top = lastelm.offset().top-top;
            $("#mess"+chat).scrollTop(top);
        });
    }
    
    var schovany = [];
    
    function schovatchat(chat){
        if(schovany[chat]){
            chatm.splice(chatm.indexOf(chat),1);
            $("#chat"+chat).animate({top: "-300px"},300);
            schovany[chat] = false;
            $("#chat"+chat+">div.head>i.min").removeClass("icon-triangle-up").addClass("icon-triangle-down");
        }else{
            chatm.push(chat);
            $("#chat"+chat).animate({top: "-25px"},300);
            schovany[chat] = true;
            $("#chat"+chat+">div.head>i.min").removeClass("icon-triangle-down").addClass("icon-triangle-up");
        }
        cookies.set('ag_chatmin',JSON.stringify(chatm));
    }
    
    function zavritchat(x){
        $("#chat"+x).remove();
        chatr.splice(chatr.indexOf(x),1);
        cookies.set('ag_chat', JSON.stringify(chatr));
    }
    
    function chat_poslat(pro,text){
    send({
       typ: "chat",
       pro: pro,
       text: text
    });
}
}


