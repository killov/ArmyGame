<!doctype html>
<html>
<head>
    <title>Armygame</title>
    <link href="<?=$cfg["dir"]?>css/jquery-ui.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=$cfg["dir"]?>css/styleg.css" type="text/css">
    <script type="text/javascript" src="<?=$cfg["dir"]?>js/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="<?=$cfg["dir"]?>js/jquery.cookies.js"></script>
    <script src="<?=$cfg["dir"]?>js/jquery-ui.min.js"></script>
    
    <script src="<?=$cfg["dir"]?>js/jquery.mousewheel.min.js"></script>
    <script type="text/javascript" src="<?=$cfg["dir"]?>js/script.js"></script>
    
    <script type="text/javascript" src="<?=$cfg["dir"]?>js/mapa.js"></script>
    <script src="<?=$cfg["dir"]?>js/jquery.waitforimages.js"></script>
    <meta charset="UTF-8">
    <script>
        d = new Date();
    $(function () {

        time_rozdil = <?php echo microtime(true)*1000;?>-d.getTime();

            $(document).tooltip({
                    track: true,
                    show: {easing: "easeInExpo", duration: 100}
            });
            $("#surovina1c").tooltip({
                    track: true,
                    content: function () {
                            return $("#surovina1c div.hidden").html();
                    }
            });
            $("#surovina2c").tooltip({
                    track: true,
                    content: function () {
                            return $("#surovina2c div.hidden").html();
                    }
            });
            $("#surovina3c").tooltip({
                    track: true,
                    content: function () {
                            return $("#surovina3c div.hidden").html();
                    }
            });
            $("#surovina4c").tooltip({
                    track: true,
                    content: function () {
                            return $("#surovina4c div.hidden").html();
                    }
            });
            $("#back").tooltip({
                    track: true,
                    content: function(){
                            return "";
                    },
                    hide: { effect: "blind", duration: 0 }
            });


    });
    var websocket;
    function ws_connect(){
        var wsUri = "ws://<?=$cfg["wsexhost"].":".$cfg["wsport"]?>/"; 	

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
        $(document).ready(function(){

        
	ws_connect();
	var c = cookies.get('ag_chat');
        chatm = cookies.get('ag_chatmin');
        if(!chatm){
            chatm = [];
        }
        for(var i in c){
            if(chatm && chatm.indexOf(c[i]) != -1){
                otevrichat(c[i],false);
            }else{
                otevrichat(c[i],true);
            }
        }
	
       
        });
        
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
    </script>
</head>
<body>
    <div id="back">
	<div id="move" style="position:absolute;top:20px;left:20px;width:1000px;height:1000px;">

	</div>
</div>
    
<div class="layout-left-top">
    <label><span id="ren"><?php echo $mesto->data["jmeno"];?></span>
	<form id="reg" action="javascript:void(1);" style="display: none">
		
		<input type="text" name="jmeno" id="in">
		
	</form></label>
    <div class="but-out-s but-1 hide-but">
        <div class="but-in-s but-pic-s">
            <div class="but-hov-s">
            </div>
            <label><i class="icon-menu"></i></label>
        </div>
    </div>
    <div class="but-out-s but-2 hide-but" onClick="mapa()">
        <div class="but-in-s but-pic-s">
            <div class="but-hov-s">
            </div>
            <label><i class="icon-hair-cross"></i></label>
        </div>
    </div>
    <div class="but-out-s but-3 hide-but" onClick="page_go('mesto')">
        <div class="but-in-s but-pic-s">
            <div class="but-hov-s">
            </div>
            <label><i class="icon-home"></i></label>
        </div>
    </div>
    <script type="text/javascript">
	$("#ren").click(function(){
		$("#ren").hide();
		$("#reg").css("display", "inline-block");
		$("#in").focus().val($("#ren").text());
	});
	
	formular_upload("#reg","index.php?post=rename",function(data){
		if(data[0] == 1){
			$("#ren").text(data[1]);
			$("#reg").hide();
			$("#ren").fadeIn(1000);
		}else{
			$("#reg").hide();
			$("#ren").fadeIn(1000);
		}
	});
		$("#in").blur(function(){
			$("#reg").hide();
			$("#ren").fadeIn(1000);
	})
    </script>
    
    <div id="suroviny">
			<div class="surovina" id="surovina0c" title="">

				<div>
					<span id="surovina0"><?=$user->penize?></span>
				</div>

			</div>
                        <div class="surovina" id="surovina1c" title="">
                            <div>
                                <span id="surovina1"><?=$mesto->surovina1?></span>
                            </div>
                            <div class="hidden">
                                <b><?=$lang[121]?></b><br>
                                <?=$lang[120]?>: <span id="surovina1_p"><?=$mesto->data["surovina1_produkce"]?></span><br>
                                <?=$lang[59]?>: <?=$mesto->data["sklad"]?>
                            </div>
			</div>
			<div class="surovina" id="surovina2c" title="">


				<div>
                                    <span id="surovina2"><?=$mesto->surovina2?></span>
				</div>
				<div class="hidden">
                                    <b><?=$lang[122]?></b><br>
                                    <?=$lang[120]?>: <span id="surovina2_p"><?=$mesto->data["surovina2_produkce"]?></span><br>
                                    <?=$lang[59]?>: <?=$mesto->data["sklad"]?>
                            </div>
			</div>
			<div class="surovina" id="surovina3c" title="">
                            <div>
                                <span id="surovina3"><?=$mesto->surovina3?></span>
                            </div>
                            <div class="hidden">
                                <b><?=$lang[123]?></b><br>
                                <?=$lang[120]?>: <span id="surovina3_p"><?=$mesto->data["surovina3_produkce"]?></span><br>
                                <?=$lang[59]?>: <?=$mesto->data["sklad"]?>
                            </div>
			</div>
			<div class="surovina" id="surovina4c" title="">
                            <div>
                                    <span id="surovina4"><?=$mesto->surovina4?></span>
                            </div>
                            <div class="hidden">
                                <b><?=$lang[124]?></b><br>
                                <?=$lang[120]?>: <span id="surovina4_p"><?=$mesto->data["surovina4_produkce"]?></span><br>
                                <?=$lang[59]?>: <?=$mesto->data["sklad"]?>
                            </div>
			</div>
		</div>
    <div class="but-out-s but-7" onClick="page_go('stat')">
        <div class="but-in-s but-pic-s">
            <div class="but-hov-s">
            </div>
            <label><i class="icon-globe"></i></label>
        </div>
    </div>
    <div class="but-out-s but-6" onClick="page_go('profil')">
        <div class="but-in-s but-pic-s">
            <div class="but-hov-s">
            </div>
            <label><i class="icon-v-card"></i></label>
        </div>
    </div>
    <div class="but-out-s but-5" onClick="page_go('statistika')">
        <div class="but-in-s but-pic-s">
            <div class="but-hov-s">
            </div>
            <label><i class="icon-cog"></i></label>
        </div>
    </div>
    <div class="but-out-s but-4">
        <a href="<?=$cfg["dir"]?>index.php?odhlas">
            <div class="but-in-s but-pic-s">
                <div class="but-hov-s">
                </div>
                <label><i class="icon-cross"></i></label>
            </div>
        </a>
    </div>
</div>
<script type="text/javascript">
        var dir = "<?=$cfg["dir"]?>";
        var surovina1 = <?=$mesto->surovina1?>;
        var surovina1_p = <?=$mesto->data["surovina1_produkce"]?>;
        var surovina2 = <?=$mesto->surovina2?>;
        var surovina2_p = <?=$mesto->data["surovina2_produkce"]?>;
        var surovina3 = <?=$mesto->surovina3?>;
        var surovina3_p = <?=$mesto->data["surovina3_produkce"]?>;
        var surovina4 = <?=$mesto->surovina4?>;
        var surovina4_p = <?=$mesto->data["surovina4_produkce"]?>;
        var sklad = <?=$mesto->data["sklad"]?>;

        setInterval("produkce()", 1000);
        setTimeout("data_load()", 1000);
</script>    



    <div class="map_options">
	<div class="map_zoom"></div>
	<div class="map_zoom_pop1">0.4x</div>
	<div class="map_zoom_pop2">0.6x</div>
	<div class="map_zoom_pop3">1x</div>
    </div>


<div id="hlaska">
</div>
<div id="pozx">
	<div id="pozxmove">

	</div>
</div>
<div id="pozy">
	<div id="pozymove">

	</div>
</div>



<script type="text/javascript">
	var mesto = <?=$mesto->data["id"]?>;
	var mesto_x = <?=$mesto->data["x"]?>;
	var mesto_y = <?=$mesto->data["y"]?>;
	var stat = <?=$user->data["stat"]?>;
	$('#back').mousewheel(function (e) {
		var sirka = parseInt($("#back").css("width").replace("px", ""));
		var vyska = parseInt($("#back").css("height").replace("px", ""))
		if (e.deltaY > 0) {
			if (velikost < 1000) {
				var ve = velikost;
				velikost = velikost + 50;
				zoom(ve, velikost, 0, 0);

				$('.map_zoom').slider('value', velikost);
			}
		}
		else {
			if (velikost > 400) {
				var ve = velikost;
				velikost = velikost - 50;
				zoom(ve, velikost, 0, 0);
				$('.map_zoom').slider('value', velikost);
			}
		}
	});
        velikost = 1000;

	$('.map_zoom').slider({
		min: 400,
		max: 1000,
		step: 50,
		value: velikost,
		slide: function (event, ui) {
			var orig = velikost;
			velikost = ui.value;
			zoom(orig, ui.value, 0, 0);
		},
		change: function (event, ui) {
			var orig = velikost;
			velikost = ui.value;
			zoom(orig, velikost, 0, 0);
		}
	});
	$('.map_zoom_pop1').click(function () {
		var orig = velikost;
		velikost = 400;
		zoom(orig, 400, 0, 0);
		$('.map_zoom').slider('value', 400);

	});
	$('.map_zoom_pop2').click(function () {
		var orig = velikost;
		velikost = 600;
		zoom(orig, 600, 0, 0);
		$('.map_zoom').slider('value', 600);

	});
	$('.map_zoom_pop3').click(function () {
		var orig = velikost;
		velikost = 1000;
		zoom(orig, 1000, 0, 0);
		$('.map_zoom').slider('value', 1000);

	});
	$("#back").mousedown(function (event) {
		tahni(event, 1);
	});
	$("#back").mousemove(function (event) {
		pohyb(event);
	});
	$("#back").mouseup(function (e) {
		pust(e);
	});
	$("#back").on('touchstart', function (e) {
		e.preventDefault();
		var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
		tahni(touch, 0);
	});
	$("#back").on('touchmove', function (e) {
		e.preventDefault();
               
		var touches = e.originalEvent.touches || e.originalEvent.changedTouches;
                if(touches.lenght=1){
                    pohyb(touches[0]);
                }

	});
	$("#back").on('touchend', function (e) {
		pust(e);
	});
	$( document ).ready(function() {
                mapload();
		mapa_pozices(<?php echo $mesto->data["x"].",".$mesto->data["y"];?>, 0);
            });
</script>
<div id="celek">
	<div id="obsah">
		<div id="obsah_h">
                    
			<?php 
                            if(isset($p[0])){
                                $cesta = "inc/hra/".$p[0].".php";
                                $cesta = strtr($cesta, './', '');
                                if(file_exists($cesta)){
                                    include $cesta; 
                                }
                            ?>
                                <script type="text/javascript">
                                
                                url = "<?php 
                                if(isset($_GET["p"])){
                                    echo $_GET["p"];
                                }
                               
                                
                                
                                ?>";
                                </script>
                            <?php
                            }else{
                                include "inc/hra/mesto.php"; 
                            }
                            
                            
                        ?>
                    
                    
		</div>
	</div>
    <div id="rip" style="height:100%"></div>
    <script>
        setlinks();
        $("#rip").click(function (event) {
		mapa();
                event.preventDefault();
	});
    </script>
</div>
<div id="levo">
    <div class="jednotky">
        <h2>Jednotky</h2>
        <div id="jednotky">
            <?php
                if($mesto->jednotky_e()){
                    echo "<table>";
                    for($i=1;$i<=8;$i++){
                        if($mesto->data["j".$i]){
                            echo "<tr><td>".$lang_jednotky[$i-1]."</td><td>".$mesto->data["j".$i]."</td></tr>";
                        }
                    }
                    echo "</table>";
                }else{
                    echo "<table><td>Žádné</td></table>";
                }
            ?>
        </div>
    </div>
</div>

<div id="chat">
    
</div>

<script>
    var chatr = [];
    var chatl = [];
    var chatm = [];
    function otevrichat(x,max){
        if(chatr.indexOf(x) == -1){
            chatr.push(x);
            cookies.set('ag_chat', JSON.stringify(chatr));
            
            $.post(dir+"index.php?post=chat",{id: x},function(data){
                var d = JSON.parse(data);
                chatl[x] = d[2];
                if(max){
                    schovany[x] = false;
                    var m = "-300";
                }else{
                    schovany[x] = true;
                    var m = "-25";
                    
                }
                $("#chat").append("<div id='chat"+x+"' style='top: "+m+"px'><div class='head'><a href='#' onclick='return false' class='prof' h='profil/"+x+"'>"+d[0]+"</a><i class='icon-cross close'></i> <i class='icon-triangle-down min'></i></div><div class='messages' id='mess"+x+"'><div>"+d[1]+"</div></div><div class='textbox' id='t"+x+"' contenteditable='true' pro='"+x+"'></div></div>");
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
    otevrichat(chat);
    $("#mess"+chat).append("<div class='"+typ+"' title='"+time+"'>"+zprava+"</div>");
    $("#mess"+chat).scrollTop(999999999);
}

function chat_poslat(pro,text){
    send({
       typ: "chat",
       pro: pro,
       text: text
    });
}


    
</script>
</body>