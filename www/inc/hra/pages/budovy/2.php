<table class="info">
    <tr><th><?=$lang[112]?>:</th><td><?=$mesto->data["surovina1_produkce"];?></td>
    <?php if($mesto->data["b2"] < $hodnoty["budovy"][2]["maximum"]){ ?>
    <th><?=$lang[113]?>:</th><td><?=$mesto->produkce(1,$mesto->data["b2"]+1)?></td>
    <?php } ?>
</tr></table>