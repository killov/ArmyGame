<table class="info">
    <tr><th><?=$lang[117]?>:</th><td><?=$mesto->sklad($mesto->data["b6"]);?></td>
    <?php if($mesto->data["b6"] < $hodnoty["budovy"][6]["maximum"]){ ?>
    <th><?=$lang[118]?>:</th><td><?=$mesto->sklad($mesto->data["b6"]+1);?></td>
    <?php } ?>
    </tr>
</table>