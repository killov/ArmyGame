<!doctype html>
<html>
	<head>
		<title>Armygame</title>
		<link rel="stylesheet" href="style.css" type="text/css">
		<script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
		<script type="text/javascript" src="js/script.js"></script>
		<meta charset="UTF-8">
	</head>
	<body>
		<div id="celek">
			<div id="menu">
				<menu>
					<li><a href="?odhlas"><?php echo $lang[19];?></a></li>
				</menu>
			</div>
			<div id="obsah">
				<h2><?php echo $lang[18];?></h2>
				<form id="log" action="javascript:void(1);">
					<table>
						<tr>
							<td><?php echo $lang[2];?>:</td>
							<td><input type="text" name="jmeno"></td>
							<td id="chyba0"></td>
						</tr>
						<tr>
							<td><?php echo $lang[20];?>:</td>
							<td>
								<select name="smer">
									<option value="0"><?php echo $lang[21];?>
									<option value="1"><?php echo $lang[22];?>
									<option value="2"><?php echo $lang[23];?>
									<option value="3"><?php echo $lang[24];?>
								</select>
							</td>
							<td id="chyba0"></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" value="<?php echo $lang[18];?>"></td>
						</tr>
					</table>
				<div id="odpoved"></div>
					<script type="text/javascript">
					
						formular_upload("#log","ajax/zpracuj/zalozitmesto.php",function(data){
							$("#odpoved").text(JSON.stringify(data));
							if(data[0] == 1)
								chyba0 = "<?php echo $lang[7];?>";
							if(data[0] == 2)
								chyba0 = "<?php echo $lang[8];?>";
							if(data[0] == 0){
								chyba0 = "";
								window.location.href = "game.php";
							}
							$("#chyba0").text(chyba0);
						});
					</script>
				</form>
			</div>
		</div>
	</body>