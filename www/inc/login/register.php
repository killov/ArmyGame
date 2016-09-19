<form id="reg" action="javascript:void(1);">
   <div class="reg-wrap">				
            <input class="reg-input" type="text" name="jmeno" placeholder="<?php echo $lang[2]; ?>">			
            <label id="chyba0"></label><br>				
            <input class="reg-input" type="text" name="email" placeholder="<?php echo $lang[3]; ?>">			
            <label id="chyba1"></label>	<br>						
            <input class="reg-input" type="password" name="heslo" placeholder="<?php echo $lang[4]; ?>">			
            <label id="chyba2"></label>	<br>						
            <input class="reg-input" type="password" name="heslo_znovu" placeholder="<?php echo $lang[5]; ?>">			
            <label id="chyba3"></label>	
   </div>
    <input class="tryit" type="submit" value="<?php echo $lang[6]; ?>"></td>		


    <script type="text/javascript">		
        formular_upload("#reg", "index.php?post=register", function(data){			
            var chyba0 = ""; 
            if (data[0] == 0)				
                chyba0 = ""; 
            if (data[0] == 1)				
                chyba0 = "<?php echo $lang[7]; ?>"; 
            if (data[0] == 2)				
                chyba0 = "<?php echo $lang[8]; ?>"; 
            if (data[0] == 3)				
                chyba0 = "<?php echo $lang[9]; ?>"; 
            if (data[1] == 0)				
                chyba1 = ""; 
            if (data[1] == 1)				
                chyba1 = "<?php echo $lang[7]; ?>"; 
            if (data[1] == 2)				
                chyba1 = "<?php echo $lang[10]; ?>"; 
            if (data[1] == 3)				
                chyba1 = "<?php echo $lang[11]; ?>"; 
            if (data[1] == 4)				
                chyba1 = "<?php echo $lang[12]; ?>"; 
            if (data[2] == 0)				
                chyba2 = ""; 
            if (data[2] == 1)				
                chyba2 = "<?php echo $lang[7]; ?>"; 
            if (data[2] == 2)				
                chyba2 = "<?php echo $lang[13]; ?>"; 
            if (data[3] == 0)				
                chyba3 = ""; 
            if (data[3] == 1)				
                chyba3 = "<?php echo $lang[7]; ?>"; 
            if (data[3] == 2)				
                chyba3 = "<?php echo $lang[14]; ?>"; 
            if (data[0] == 0 && data[1] == 0 && data[2] == 0 && data[3] == 0){				
                window.location.href = "index.php";		
            }			
            $("#chyba0").text(chyba0); 
            $("#chyba1").text(chyba1); 
            $("#chyba2").text(chyba2); 
            $("#chyba3").text(chyba3); 
        });
            </script>
</form>