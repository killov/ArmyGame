<script type="text/javascript">
	$(".zpravy").removeClass("zpravyn");
</script>
<h2><?php echo $lang[41];?>
<span class="zp" ><a href="#"onMouseDown="page_load('zpravy&napsat')" onMouseUp="page_draw()"><?php echo $lang[46];?></a></span>
<span class="zp" ><a href="#"onMouseDown="page_load('zpravy')" onMouseUp="page_draw()"><?php echo $lang[45];?></a></span>
</h2>
<?php
if(isset($_GET["zid"])){
	include "zpravy/zprava.php";
}
elseif(isset($_GET["napsat"])){
	include "zpravy/napsat.php";
}else{
	include "zpravy/dorucene.php";
}
?>