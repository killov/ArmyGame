<table class="info">
    <tr><th><?=$lang[112]?>:</th><td><?=$mesto->produkce(4,$mesto->data["b5"]);?></td></tr>
    <?php if($mesto->data["b5"] < $hodnoty["budovy"][5]["maximum"]){ ?>
    <tr><th><?=$lang[113]?>:</th><td><?=$mesto->produkce(4,$mesto->data["b5"]+1)?></td></tr>
    <?php } ?>
    <tr><th><?=$lang[114]?>:</th><td><?=$mesto->data["populace"]?></td></tr>
    <tr><th><?=$lang[115]?>:</th><td><?=$mesto->jednotky_spotreba()?></td></tr>
    <tr><th><?=$lang[116]?>:</th><td><?=$mesto->data["surovina4_produkce"]?></td></tr>
</table>