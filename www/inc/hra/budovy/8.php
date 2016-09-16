<table class="info">
    <tr><th><?=$lang[117]?>:</th><td><?=$user->banka($mesto->data["b8"]);?></td></tr>
    <?php if($mesto->data["b8"] < $hodnoty["budovy"][8]["maximum"]){ ?>
    <tr><th><?=$lang[118]?>:</th><td><?=$user->banka($mesto->data["b8"]+1);?></td></tr>
    <?php } ?>
    <tr><th><?=$lang[119]?>:</th><td><?=$user->data["banka"];?></td></tr>
</table>

