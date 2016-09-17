<table class="info">
    <tr><th><?=$lang[117]?>:</th><td><?=$user->banka($mesto->data["b8"]);?></td>
    <?php if($mesto->data["b8"] < $hodnoty["budovy"][8]["maximum"]){ ?>
    <th><?=$lang[118]?>:</th><td><?=$user->banka($mesto->data["b8"]+1);?></td>
    <?php } ?>
    <th><?=$lang[119]?>:</th><td><?=$user->data["banka"];?></td></tr>
</table>

