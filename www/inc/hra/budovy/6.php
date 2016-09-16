<table class="info">
    <tr><th><?=$lang[117]?>:</th><td><?=$mesto->sklad($mesto->data["b6"]);?></td></tr>
    <?php if($mesto->data["b6"] < $hodnoty["budovy"][6]["maximum"]){ ?>
    <tr><th><?=$lang[118]?>:</th><td><?=$mesto->sklad($mesto->data["b6"]+1);?></td></tr>
    <?php } ?>
</table>