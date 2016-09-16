<table class="info">
    <tr><th><?=$lang[112]?>:</th><td><?=$mesto->data["surovina3_produkce"];?></td></tr>
    <?php if($mesto->data["b4"] < $hodnoty["budovy"][4]["maximum"]){ ?>
    <tr><th><?=$lang[113]?>:</th><td><?=$mesto->produkce(3,$mesto->data["b4"]+1)?></td></tr>
    <?php } ?>
</table>