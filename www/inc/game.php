<!doctype html>
<html>
<head>
    <title>Armygame</title>
    <link rel="icon" type="image/ico" href="favicon.ico">
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
    var wsUri = "ws://<?=$cfg["wsexhost"].":".$cfg["wsport"]?>/"; 
    
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
                                $cesta = "inc/hra/pages/".$p[0].".php";
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

<div id="faq">
    <h2><?=$lang[136]?>
    <i class="icon-cross close" onclick="faq_close()"></i>
    </h2>
    <div id="faq_obsah">
</div>

<div id="chat">
    
</div>

</body>