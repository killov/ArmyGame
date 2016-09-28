
<?php

if($user->data["stat"] or isset($_GET["id"])){
$st = new stat();
if(isset($_GET["id"])){
    $stat = $st->info($_GET["id"]);       
}else{
    $stat = $st->info($user->data["stat"]);   
}

if(!$stat){
    exit;
}
$s = $st->users($stat["id"]);
?>
	<h2><?php echo $lang[89]." ".htmlspecialchars($stat["jmeno"]);?>
            <?php if($stat["id"] == $user->data["stat"]){ ?>
		<span class="zp" >
			<a href="#" h="stat&s=moznosti" class="link"><?php echo $lang[94];?></a>
		</span>
		<span class="zp" >
			<a href="#" h="stat&s=forum" class="link"><?php echo $lang[93];?></a>
		</span>
		<span class="zp" >
			<a href="#" h="stat" class="link"><?php echo $lang[92];?></a>
		
		</span>

            <?php } ?>
	</h2>
	
<?php
if(isset($_GET["s"]) and $_GET["s"] == "moznosti"){
    if(isset($_GET["m"]) && $_GET["m"] == "pozvat" && $user->data["sp_all"]){
        ?>
        <table class="dorucene">
            <tr><th><?php echo $lang[95];?></th></tr>
            <tr><td>
                    <form id="stat" action="javascript:void(1);">
					<table>
						<tr>
                                                    <td><?php echo $lang[2];?>:</td>
                                                    <td><input type="text" name="jmeno"></td>
                                                    <td id="chyba0"></td>
						</tr>
						<tr>
                                                    <td></td>
                                                    <td><input type="submit" value="<?php echo $lang[95];?>"></td>
						</tr>
					</table>
				<div id="odpoved"></div>
		
				</form>
                                <script type="text/javascript">
                                    formular_upload("#stat","index.php?post=statpozvat",function(data){
                                        $("#odpoved").text(JSON.stringify(data));
                                        if(data[0] == 1)
                                                chyba0 = "<?php echo $lang[7];?>";
                                        if(data[0] == 2)
                                                chyba0 = "<?php echo $lang[97];?>";
                                        if(data[0] == 3)
                                            chyba0 = "<?php echo $lang[98];?>";
                                        if(data[0] == 4)
                                            chyba0 = "<?php echo $lang[99];?>";
                                        if(data[0] == 5)
                                            chyba0 = "<?php echo $lang[100];?>";
                                        if(data[0] == 0){
                                                chyba0 = "";
                                                page_refresh();
                                        }
                                        $("#chyba0").text(chyba0);
                                });
                                </script>
                </td>
            </tr>
        </table>

<table class="profil3">

<?php
$s = $st->pozvanky($stat["id"]);
if($s){
	echo "<tr><th colspan='2'>".$lang[96]."</th></tr>";
	foreach($s as $d){
		echo "<tr><td><a href=\"#\" h=\"profil&uid=".$d["user"]."\" class=\"link\">".$d["userjmeno"]."</a></td><td><a href=\"#\" onclick=\"pozvankazrusit('".$d["id"]."')\">".$lang["101"]."</a></td></tr>";
	}
}
?>

</table>
        <?php
    }else{
        ?>
        <table class="dorucene">
            <tr><th><?php echo $lang[94];?></th></tr>
            <tr><td>
                    <?php if($user->data["sp_all"]){ ?>
                    <a href="#" h="stat&s=moznosti&m=pozvat" class="link"><?php echo $lang[95];?></a><br>
                    <?php } ?>
                    <a href="#" onclick="opustitstat();return false"><?php echo $lang[104];?></a>
                </td>
            </tr>
        </table>
        <?php
    }
}elseif(isset($_GET["s"]) and $_GET["s"] == "forum"){
	
}else{
?>
	<table class="profil">
<tr><th><?php echo $lang[34];?></th><th><?php echo $lang[35];?></th></tr>
<tr>
<td>
	<table class="profil1">
		<tr><td><?php echo $lang[40];?>: </td><td><?php echo $stat["poradi"];?></td></tr>
		<tr><td><?php echo $lang[36];?>: </td><td><?php echo $stat["pop"];?></td></tr>
		<tr><td><?php echo $lang[91];?>: </td><td><?php echo $stat["clenu"];?></td></tr>
	</table>
</td>
<td>

</td>
</tr>
</table>

<table class="profil3">
<tr><th><?php echo $lang[40];?></th><th><?php echo $lang[32];?></th><th><?php echo $lang[56];?></th><th><?php echo $lang[36];?></th></tr>
<?php
if($s){
	$x = 1;
	foreach($s as $d){
		echo "<tr><td>".$x."</td><td><a href=\"#\" onMouseDown=\"page_load('profil&uid=".$d["id"]."')\" onMouseUp=\"page_draw()\">".htmlspecialchars($d["jmeno"])."</a></td><td>".$d["mest"]."</td><td>".$d["pop"]."</td></tr>";
	
                $x++;
        }
	
}
?>

</table>
	<?php
}
}else{
?>



<h2><?php echo $lang[89];?></h2>


<table class="profil3">

<?php
$st = new stat();
$s = $st->pozvanky_hrac($user->data["id"]);
if($s){
	echo "<tr><th colspan='2'>".$lang[102]."</th></tr>";
	foreach($s as $d){
		echo "<tr><td>".htmlspecialchars($d["statjmeno"])."</a></td><td><a href=\"#\" onclick=\"pozvankapotvrdit('".$d["id"]."')\">".$lang[103]."</a></td></tr>";
	}
}
?>

</table>

<table class="dorucene">
<tr><th><?php echo $lang[90];?></th></tr>
<tr><td>
<form id="stat" action="javascript:void(1);">
					<table>
						<tr>
							<td><?php echo $lang[2];?>:</td>
							<td><input type="text" name="jmeno"></td>
							<td id="chyba0"></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" value="<?php echo $lang[90];?>"></td>
						</tr>
					</table>
				<div id="odpoved"></div>
		
				</form>
							<script type="text/javascript">
						formular_upload("#stat","index.php?post=zalozitstat",function(data){
							$("#odpoved").text(JSON.stringify(data));
							if(data[0] == 1)
								chyba0 = "<?php echo $lang[7];?>";
							if(data[0] == 2)
								chyba0 = "<?php echo $lang[8];?>";
							if(data[0] == 0){
								chyba0 = "";
                                                                stat = data[1];
                                                                page_refresh();
							}
							$("#chyba0").text(chyba0);
						});
					</script>
</td></tr>

</table>
	<?php
}
?>