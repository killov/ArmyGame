<!doctype html>

<html>

<head>
    <meta charset="UTF-8">
    <meta authors="Zdenek Mazurak, Ales Dopita">
    <title>ArmyGame | Login</title>
    <meta name="msapplication-TileImage" content="img/armygame-monogram-w.png"/>
    <meta name="msapplication-TileColor" content="#72A645"/>
    <link rel="icon" type="image/ico" href="favicon.ico">
    <link rel="apple-touch-icon" href="img/armygame-monogram-bw.png">
    <link rel="shortcut icon" href="img/armygame-monogram-bw.png">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="font/style.css">

    <script src="http://www.armygame.eu/js/jquery-2.2.0.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>

</head>

<body>
<header class="main">
    <div id="logo">
    </div>
</header>

<div class="description center" id="celek" style="display: block;">

 

    <div id="obsah">

        <h2><?php echo $lang[18];?></h2>
            <form id="log" action="javascript:void(1);">
                 <td id="chyba0"></td>
                <div class="login-wrap">
                   
                    <input  class="login-input"type="text" name="jmeno" placeholder="<?php echo $lang[2];?>">
                    <select  class="login-input" name="smer">
                        <option value="0"><?php echo $lang[21];?>
                        <option value="1"><?php echo $lang[22];?>
                        <option value="2"><?php echo $lang[23];?>
                        <option value="3"><?php echo $lang[24];?>
                    </select>
                    
                </div>
                 <input class="tryit" type="submit" value="<?php echo $lang[18];?>">
					<script type="text/javascript">
					
						formular_upload("#log","index.php?ok",function(data){
							if(data[0] == 1)
								chyba0 = "<?php echo $lang[7];?>";
							if(data[0] == 2)
								chyba0 = "<?php echo $lang[8];?>";
							if(data[0] == 0){
								chyba0 = "";
								window.location.href = "index.php";
							}
							$("#chyba0").text(chyba0);
						});
					</script>
				</form>

    </div>

</div>

<div class="map" data-stellar-background-ratio="0.6"></div>
<div class="center contact">
    <div class="cont">
        <i class="icon-mail"></i> <a href="mailto:support@armygame.eu">support@armygame.eu</a>
    </div>
    <div class="cont">
        <i class="icon-facebook2"></i> <a href="https://www.facebook.com/webarmygame/" target="_blank">ArmyGame</a>
    </div>
    <div class="cont">
        <i class="icon-paypal pay"></i>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0"
                   name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
    </div>
</div>
<footer class="main">
    <div class="center">
        <label id="copy">&copy; 2017 | Zdeněk Mazurák & <a href="http://aloushweb.cz/" target="_blank">Aleš
                Dopita</a></label>
    </div>
</footer>

<script src="http://www.armygame.eu/js/jquery.stellar.min.js"></script>
<script>
    var dir = "<?=$cfg["dir"]?>";
    $(function () {
        var oldel;
        $(window).stellar();
        //$('.welcome h1, .welcome h2, .description').hide().fadeIn(1000);
        $('#logo').mouseenter(function () {
            $(this).stop().animate({width: 500}, 200).animate({width: 420}, 120);
        });
        $('.images li').mouseenter(function () {
            $(this).find('i').stop().fadeIn();
        });
        $('.images li').mouseleave(function () {
            $(this).find('i').stop().fadeOut();
        });

        $('menu a').click(function () {
            oldel = $('a.active');
            $(this).addClass('active');
            $(oldel).removeClass('active');
        });
    });
</script>

</body>

</html>