<table class="info">
    <tr><th><?=$lang[112]?>:</th><td><?=$mesto->data["surovina2_produkce"];?></td>
    <?php if($mesto->data["b3"] < $hodnoty["budovy"][3]["maximum"]){ ?>
    <th><?=$lang[113]?>:</th><td><?=$mesto->produkce(2,$mesto->data["b3"]+1)?></td>
    <?php } ?>
    </tr>
</table>