<p>
    <?php echo $lang[83].": ".$mesto->obchodnici_dostupni($mesto->data["b9"])."/".$mesto->obchodnici($mesto->data["b9"]); ?>
</p>
<table class="dorucene">
    <span id="zm1" style="display: none">Prodat suroviny | <a href="#" onclick="trh.zmena();return false">Koupit suroviny</a></span>
    <span id="zm2" style="display: none"><a href="#" onclick="trh.zmena();return false">Prodat suroviny</a> | Koupit suroviny</span>

    <div>
        <tr><th id="zm">Prodat suroviny | <a href="#" onclick="trh.zmena();return false">Koupit suroviny</a></th></tr>
        <tr>
            <td>
                <form id="trhprodat">
                    <span class="surovina1"><input name="surovina1" id="sur1" size="5"></span>
                    <span class="surovina2"><input name="surovina2" id="sur2" size="5"></span>
                    <span class="surovina3"><input name="surovina3" id="sur3" size="5"></span>
                    <span class="surovina4"><input name="surovina4" id="sur4" size="5"></span>
                    <span class="spotreba" id="obch">0</span>
                    
                    <span class="cas"><?php echo cas($hodnoty["trziste"]["delka"]);?></span>
                    <span id="arr">&nbsp;</span>
                    <span class="surovina0" id="sur0">0</span>
                    <input type="hidden" id="typ" value="0" name="typ">
                    <br>
                    
                    <input type="submit">
                </form>
            </td>
        </tr>
        <script type="text/javascript">
            trh = new Trh();
            
            trh.pomer = <?php echo $hodnoty["trziste"]["pomer"];?>;
            trh.nosnost = <?php echo $hodnoty["trziste"]["nosnost"];?>;
            
            $(function(){
                $("#sur1").keyup(function(){
                    trh.prepocet()
                });
                $("#sur2").keyup(function(){
                    trh.prepocet()
                });
                $("#sur3").keyup(function(){
                    trh.prepocet()
                });
                $("#sur4").keyup(function(){
                    trh.prepocet()
                });
                formular_upload("#trhprodat","index.php?post=trh",function(data){
                    if(data[0] == 1)
                            chyba0 = "<?php echo $lang[77];?>";
                    if(data[0] == 2)
                            chyba0 = "<?php echo $lang[78];?>";
                    if(data[0] == 3)
                        chyba0 = "Nedostatek peněz";
                    if(data[0] == 4)
                        chyba0 = "Nevyplněno";
                    if(data[0] == 0){
                            chyba0 = "chuj";
                            page_refresh();
                    }
                    data_load();
                    hlaska(chyba0,1);
		});
            });
        </script>
    </div>
    
</table>

<?php
    $transport = $mesto->obchod_transport();
    if($transport){
        echo "<table class=\"dorucene\">";
        foreach($transport as $t){
            
            if($t["budova"] == 2){
                echo "<tr><th>Dovoz surovin za <span id=odpv".$t["id"].">".cas($t["cas"]-time())."</span></th></tr>";
                echo "<tr><td><span class=\"surovina1\">".$t["surovina1"]."</span>
                    <span class=\"surovina2\">".$t["surovina2"]."</span>
                    <span class=\"surovina3\">".$t["surovina3"]."</span>
                    <span class=\"surovina4\">".$t["surovina4"]."</span></td></tr>";
            }
            if($t["budova"] == 1){
                echo "<tr><th>Prodej surovin za <span id=odpv".$t["id"].">".cas($t["cas"]-time())."</span></th></tr>";
                echo "<tr><td><span class=\"surovina0\">".$t["surovina1"]."</span></td></tr>";
            }
        }
        echo "</table>";
        ?>
<script type="text/javascript">
    <?php 
        foreach($transport as $t){
            echo "g_odpocitavacv[".$t["id"]."] = ".($t["cas"]).";";
        }
    ?>
        odpocitejv();
</script>



        <?php
    }
?>
