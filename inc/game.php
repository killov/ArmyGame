<!doctype html>
<html>
<head>
    <title>Armygame</title>
    <link rel="icon" type="image/ico" href="favicon.ico">
    <link href="<?= $cfg["dir"] ?>css/jquery-ui.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $cfg["dir"] ?>css/styleg.css" type="text/css">
    <script type="text/javascript" src="<?= $cfg["dir"] ?>js/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="<?= $cfg["dir"] ?>js/jquery.cookies.js"></script>
    <script src="<?= $cfg["dir"] ?>js/jquery-ui.min.js"></script>

    <script src="<?= $cfg["dir"] ?>js/jquery.mousewheel.min.js"></script>
    <script type="text/javascript" src="<?= $cfg["dir"] ?>js/script.js"></script>
    <script type="text/javascript" src="<?= $cfg["dir"] ?>js/game.js"></script>
    <script type="text/javascript" src="<?= $cfg["dir"] ?>js/chat.js"></script>
    <script type="text/javascript" src="<?= $cfg["dir"] ?>js/map.js"></script>
    <script type="text/javascript" src="<?= $cfg["dir"] ?>js/sendUnits.js"></script>
    <?php if ($cfg["map"] == 1) { ?>
        <script type="text/javascript" src="<?= $cfg["dir"] ?>js/map2d.js"></script>
    <?php }else{ ?>
        <style type="text/css">
            html, body, canvas {
                padding: 0;
                margin: 0;
            }

            .loader{
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
            }

            .loader .icon{
                position: absolute;
                bottom: 200px;
                width: 100%;
                text-align: center;
                font-size: 2em;
                color: #fdfdfd;
                text-shadow: 0 0 3px #999;
            }
            .loader .bar{
                position: absolute;
                bottom: 150px;
                width: 80%;
                height: 25px;
                left: 10%;
                border: 5px inset #fdfdfd;
                border-radius: 18px;
            }
            .loader .bar_wrap{
                position: relative;
                border-radius: 15px;
            }

            .loader .progress{
                height: 25px;
                width: 0%;
                box-shadow: inset 3px 3px 5px #555;
                border-radius: 15px;
                background: #72A645;
            }

            .stats {
                display: none;
                position: absolute;
                top: 50px;
                right: 0;
                width: 250px;
                padding: 15px;
                color: #fdfdfd;
                background: rgba(0, 0, 0, 0.7);
                border-radius: 3px;
            }

            .stats #toggleDebugPanel{
                float: right;
            }

            .stats h2{
                margin: -2px 0 5px 0;
                border-bottom: 1px solid #cccccc;
            }

            .stats h3{
                margin: 10px 0;
                border-bottom: 1px solid #888;
            }

            .stats label {
                vertical-align: bottom;
                display: inline-block;
                width: 130px;
                overflow: hidden;
            }
        </style>
        <script id="vst1" type="x-shader/x-vertex">
		precision highp float;
		uniform mat4 modelViewMatrix;
		uniform mat4 projectionMatrix;
		uniform vec3 light;
		uniform float time;
		uniform float dirIntensity;
		uniform float hemiIntensity;
		uniform vec3 dirColor;
		attribute vec3 position;
		attribute vec3 offset;
		attribute vec3 normal;
		attribute vec3 color;
		vec3 light2;
		vec3 col;
		varying vec3 vPosition;
		varying vec4 vColor;
		varying vec3 vNormal;
		float rand(vec2 co){
            return fract(sin(dot(co.xy ,vec2(12.9898,78.233))) * 43758.5453);
        }
		void main(){
			float x = offset.x + offset.y;
			x = x * 1024.0;
			mat3 m = mat3(cos(x), 0, sin(x), 0, 1, 0, -sin(x), 0, cos(x));
			mat3 mn = mat3(cos(-x), 0, -sin(-x), 0, 1, 0, sin(-x), 0, cos(-x));
		    vNormal = mn * normal;
			vec3 vPosition = m * position;
			float koef = 0.10;
		    if(color.g > 0.25){
		        vPosition.y += koef * sin(time*(rand(position.xz) * 1.0));
		        vPosition.x += koef * sin(time*(rand(position.yz) * 1.0));
		        vPosition.z += koef * sin(time*(rand(position.yx) * 1.0));
		    }
		    light2 = light;
		    light2 = normalize(light);
		    float d = clamp(dot(light2, vNormal), 0.0, 1.0);
		    d = d * hemiIntensity + dirIntensity;
		    vec3 c = d * dirColor;
            col = c * color;
            vPosition = offset + (vPosition);
			vColor = vec4(col, 0.1);
			gl_Position = projectionMatrix * modelViewMatrix * vec4( vPosition, 1.0 );
		}

        </script>


        <script id="vsw1" type="x-shader/x-vertex">
		precision highp float;
		uniform mat4 modelViewMatrix;
		uniform mat4 projectionMatrix;
		uniform vec3 light;
		uniform float time;
		uniform float dirIntensity;
		uniform float hemiIntensity;
		uniform vec3 dirColor;
		attribute vec3 position;
		attribute vec3 offset;
		attribute vec3 normal;
		attribute vec3 color;
		vec3 light2;
		vec3 col;
		varying vec3 vPosition;
		varying vec4 vColor;
		varying vec3 vNormal;
		float rand(vec2 co){
            return fract(sin(dot(co.xy ,vec2(12.9898,78.233))) * 43758.5453);
        }
		float rand2(vec2 co){
            return fract(sin(dot(co.xy ,co.xy)) * 43758.0);
        }
		void main(){
			float x = offset.x + offset.y;
			x = x * 64.0;
			mat3 m = mat3(cos(x), 0, sin(x), 0, 1, 0, -sin(x), 0, cos(x));
			mat3 mn = mat3(cos(-x), 0, -sin(-x), 0, 1, 0, sin(-x), 0, cos(-x));
		    vNormal = mn * normal;
			vec3 vPosition = m * position;
			float koef = 0.14;
            vPosition.z += koef * sin(time*(rand2(position.yx) * 1.0));
		    light2 = light;
		    light2 = normalize(light);
		    float d = clamp(dot(light2, vNormal), 0.0, 1.0);
		    d = d * hemiIntensity + dirIntensity;
		    vec3 c = d * dirColor;
		    float koefrand = vPosition.z * 1.0;
            col = c * vec3( 0.32 + (koef * koefrand), 0.42 + (koef * koefrand), 0.72 + (koef * koefrand));
            vPosition = offset + (vPosition);
			vColor = vec4(col, 0.72);
			gl_Position = projectionMatrix * modelViewMatrix * vec4( vPosition, 1.0 );
		}

        </script>

        <script id="fst1" type="x-shader/x-fragment">
		precision highp float;
		varying vec3 vPosition;
		varying vec4 vColor;
		varying vec3 vNormal;
		void main() {
			gl_FragColor = vColor;
		}

        </script>


        <script type="text/javascript" src="<?= $cfg["dir"] ?>js/3d/three.min.js"></script>
        <script type="text/javascript" src="<?= $cfg["dir"] ?>js/3d/stats.min.js"></script>
        <script type="text/javascript" src="<?= $cfg["dir"] ?>js/3d/ColladaLoader.js"></script>
        <script type="text/javascript" src="<?= $cfg["dir"] ?>js/3d/OrbitControls.js"></script>
        <script type="text/javascript" src="<?= $cfg["dir"] ?>js/map3d.js"></script>
    <?php } ?>

    <script src="<?= $cfg["dir"] ?>js/jquery.waitforimages.js"></script>
    <meta charset="UTF-8">
    <script>
        d = new Date();
        $(function () {

            game.time_rozdil = <?php echo microtime(true) * 1000;?>-d.getTime();

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
            $("#map_svg").tooltip({
                track: true,
                content: function () {
                    return "d";
                },
                hide: {effect: "blind", duration: 0}
            });


        });

        var game = new Game();
        $(function () {
            game.dir = "<?=$cfg["dir"]?>";
            game.lang = <?=json_encode([
                "game" => $lang,
                "jednotky" => $lang_jednotky,
                "vyzkum" => $lang_vyzkum
            ])?>;
            game.timeZone = <?=(new DateTime())->getOffset()?>;
            game.mesto.surovina1 = <?=$mesto->surovina1?>;
            game.mesto.surovina1_p = <?=$mesto->data["surovina1_produkce"]?>;
            game.mesto.surovina2 = <?=$mesto->surovina2?>;
            game.mesto.surovina2_p = <?=$mesto->data["surovina2_produkce"]?>;
            game.mesto.surovina3 = <?=$mesto->surovina3?>;
            game.mesto.surovina3_p = <?=$mesto->data["surovina3_produkce"]?>;
            game.mesto.surovina4 = <?=$mesto->surovina4?>;
            game.mesto.surovina4_p = <?=$mesto->data["surovina4_produkce"]?>;
            game.mesto.sklad = <?=$mesto->data["sklad"]?>;
            game.mesto.id = <?=$mesto->data["id"]?>;
            game.mesto.x = <?=$mesto->data["x"]?>;
            game.mesto.y = <?=$mesto->data["y"]?>;
            game.mesto.jmeno = "<?=htmlspecialchars($mesto->data["jmeno"])?>";
            game.mesto.jednotky = <?=json_encode($mesto->jednotky())?>;
            game.stat = <?=$user->data["stat"]?>;
            game.wsUri = "ws://<?=$cfg["wsexhost"] . ":" . $cfg["wsport"]?>/";


            game.init();
            game.sendUnits.data = <?=json_encode($hodnoty["jednotky"])?>;
            new Mapa(game.mapControl).init(<?php echo $mesto->data["x"] . "," . $mesto->data["y"];?>);
        });
    </script>
</head>
<body>
<div id="back">
    <?php if ($cfg["map"] == 1) { ?>
        <div id="move" style="position:absolute;top:20px;left:20px;width:1000px;height:1000px;">
            <svg id="map_svg" viewBox="0 0 40000 40000" title></svg>
        </div>
    <?php } else { ?>

        <div class="stats">
            <button id="toggleDebugPanel">Show/hide</button>
            <h2>Debug panel</h2>
            <div class="stats-content">
                <h3>Camera</h3>
                <ul>
                    <li>pos.x = <label id="camPosX"></label></li>
                    <li>pos.y = <label id="camPosY"></label></li>
                    <li>pos.z = <label id="camPosZ"></label></li>
                    <li>rot.x = <label id="camRotX"></label></li>
                    <li>rot.y = <label id="camRotY"></label></li>
                    <li>rot.z = <label id="camRotZ"></label></li>
                    <li>zoom = <label id="camZoom"></label></li>
                </ul>
                <h3>Controls</h3>
                <button id="shadowsOff">Shadows OFF</button>
                <button id="shadowsOn">Shadows ON</button>
                <br>
                <br>
                <button id="makeBlock">Load map</button>
                <button id="hideBlock">Hide (wip)</button><br><br>
                <input type="text" id="game-time"> <button id="load-time">Change</button>
                <button id="animate-time">1 day in 24 seconds</button>
            </div>
        </div>
    <?php } ?>
</div>
<?php if ($cfg["map"] == 2) { ?>
    <div class="loader">
        <div class="icon">
            Loading..
        </div>
        <div class="bar">
            <div class="bar_wrap">
                <div class="progress">

                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="layout-left-top">
    <label><span id="ren"><?php echo $mesto->data["jmeno"]; ?></span>
        <form id="reg" action="javascript:void(1);" style="display: none">

            <input type="text" name="jmeno" id="in">

        </form>
    </label>
    <div class="but-out-s but-1 hide-but">
        <div class="but-in-s but-pic-s">
            <div class="but-hov-s">
            </div>
            <label><i class="icon-menu"></i></label>
        </div>
    </div>
    <div class="but-out-s but-2 hide-but" onClick="game.mapa()">
        <div class="but-in-s but-pic-s">
            <div class="but-hov-s">
            </div>
            <label><i class="icon-hair-cross"></i></label>
        </div>
    </div>
    <div class="but-out-s but-3 hide-but" onClick="game.page_go('mesto')">
        <div class="but-in-s but-pic-s">
            <div class="but-hov-s">
            </div>
            <label><i class="icon-home"></i></label>
        </div>
    </div>
    <script type="text/javascript">
        $("#ren").click(function () {
            $("#ren").hide();
            $("#reg").css("display", "inline-block");
            $("#in").focus().val($("#ren").text());
        });

        game.formular_upload("#reg", "index.php?post=rename", function (data) {
            if (data[0] == 1) {
                $("#ren").text(data[1]);
                $("#reg").hide();
                $("#ren").fadeIn(1000);
            } else {
                $("#reg").hide();
                $("#ren").fadeIn(1000);
            }
        });

        $("#in").blur(function () {
            $("#reg").hide();
            $("#ren").fadeIn(1000);
        })
    </script>

    <div id="suroviny">
        <div class="surovina" id="surovina0c" title="">

            <div>
                <span id="surovina0"><?= $user->penize ?></span>
            </div>

        </div>
        <div class="surovina" id="surovina1c" title="">
            <div>
                <span id="surovina1"><?= $mesto->surovina1 ?></span>
            </div>
            <div class="hidden">
                <b><?= $lang[121] ?></b><br>
                <?= $lang[120] ?>: <span id="surovina1_p"><?= $mesto->data["surovina1_produkce"] ?></span><br>
                <?= $lang[59] ?>: <?= $mesto->data["sklad"] ?>
            </div>
        </div>
        <div class="surovina" id="surovina2c" title="">


            <div>
                <span id="surovina2"><?= $mesto->surovina2 ?></span>
            </div>
            <div class="hidden">
                <b><?= $lang[122] ?></b><br>
                <?= $lang[120] ?>: <span id="surovina2_p"><?= $mesto->data["surovina2_produkce"] ?></span><br>
                <?= $lang[59] ?>: <?= $mesto->data["sklad"] ?>
            </div>
        </div>
        <div class="surovina" id="surovina3c" title="">
            <div>
                <span id="surovina3"><?= $mesto->surovina3 ?></span>
            </div>
            <div class="hidden">
                <b><?= $lang[123] ?></b><br>
                <?= $lang[120] ?>: <span id="surovina3_p"><?= $mesto->data["surovina3_produkce"] ?></span><br>
                <?= $lang[59] ?>: <?= $mesto->data["sklad"] ?>
            </div>
        </div>
        <div class="surovina" id="surovina4c" title="">
            <div>
                <span id="surovina4"><?= $mesto->surovina4 ?></span>
            </div>
            <div class="hidden">
                <b><?= $lang[124] ?></b><br>
                <?= $lang[120] ?>: <span id="surovina4_p"><?= $mesto->data["surovina4_produkce"] ?></span><br>
                <?= $lang[59] ?>: <?= $mesto->data["sklad"] ?>
            </div>
        </div>
    </div>
    <div class="but-out-s but-7" onClick="game.page_go('stat')">
        <div class="but-in-s but-pic-s">
            <div class="but-hov-s">
            </div>
            <label><i class="icon-globe"></i></label>
        </div>
    </div>
    <div class="but-out-s but-6" onClick="game.page_go('profil')">
        <div class="but-in-s but-pic-s">
            <div class="but-hov-s">
            </div>
            <label><i class="icon-v-card"></i></label>
        </div>
    </div>
    <div class="but-out-s but-5" onClick="game.page_go('statistika')">
        <div class="but-in-s but-pic-s">
            <div class="but-hov-s">
            </div>
            <label><i class="icon-cog"></i></label>
        </div>
    </div>
    <div class="but-out-s but-4">
        <a href="<?= $cfg["dir"] ?>index.php?odhlas">
            <div class="but-in-s but-pic-s">
                <div class="but-hov-s">
                </div>
                <label><i class="icon-cross"></i></label>
            </div>
        </a>
    </div>
</div>

<div id="hlaska">
</div>
<?php if ($cfg["map"] == 1) { ?>

    <div class="map_options">
        <div class="map_zoom"></div>
        <div class="map_zoom_pop1">0.4x</div>
        <div class="map_zoom_pop2">0.6x</div>
        <div class="map_zoom_pop3">1x</div>
    </div>


    <div id="pozx">
        <div id="pozxmove">

        </div>
    </div>
    <div id="pozy">
        <div id="pozymove">

        </div>
    </div>
<?php } ?>
<div id="celek">
    <div id="obsah">
        <div id="obsah_h">

            <?php
            if (isset($p[0])) {
                $cesta = "../inc/hra/pages/" . $p[0] . ".php";
                $cesta = strtr($cesta, './', '');
                if (file_exists($cesta)) {
                    include $cesta;
                }
                ?>
                <script type="text/javascript">

                    game.url = "<?php
                        if (isset($_GET["p"])) {
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
        game.setlinks();
        $("#rip").click(function (event) {
            game.mapa();
            event.preventDefault();
        });
    </script>
</div>
<div id="levo">
    <div class="jednotky" id="jed">
        <h2 onClick="game.page_go('jednotky')"><?=$lang[147]?></h2>
        <div id="jednotky">
            <?php
            if ($mesto->jednotky_e()) {
                echo "<table>";
                for ($i = 1; $i <= 8; $i++) {
                    if ($mesto->data["j" . $i]) {
                        echo "<tr><td>" . $lang_jednotky[$i - 1] . "</td><td>" . $mesto->data["j" . $i] . "</td></tr>";
                    }
                }
                echo "</table>";
            } else {
                echo "<table><td>Žádné</td></table>";
            }
            ?>
        </div>
    </div>
    <div id="cont" class="jednotky">
        <h2 onClick="game.page_go('jednotky')"><?=$lang[137]?></h2>
        <form id="su_form">
            
            <table>
                <tr><td><?=$lang[25]?></td><td id="su_city"></td></tr>
                <tr><td><?=$lang[32]?></td><td id="su_user"></td></tr>
                <tr><td><?=$lang[89]?></td><td id="su_stat"></td></tr>
                <tr><td><?=$lang[138]?></td><td id="su_distance"></td></tr>
                <tr><td><?=$lang[139]?></td><td id="su_time"></td></tr>
                <tr><td><?=$lang[80]?></td><td id="su_coming_time"></td></tr>
                <tr><td><?=$lang[140]?></td><td id="su_capacity"></td></tr>
            </table>
            <select style="width: 100%" id="su_source">
                <option value="0">Město</option>
            </select>
            <table>
                <?php for($i = 1;$i<=8;$i++){ ?>
                <tr>
                    <td><?=$lang_jednotky[$i-1]?></td>
                    <td><input id="pj<?=$i?>" size="5"></td>
                    <td><a href="#" id="pjk<?=$i?>" class="pjk" j="<?=$i?>"><?=$mesto->data["j".$i]?></a></td>
                </tr>
                <?php } ?>
            </table>
            <input type="submit">
        </form>
    </div>
</div>

<div id="faq">
    <h2><?= $lang[136] ?>
        <i class="icon-cross close" onclick="game.faq_close()"></i>
    </h2>
    <div id="faq_obsah"></div>
</div>
<div id="chat">

</div>

</body>