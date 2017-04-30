<form id="log" action="javascript:void(1);">

    <label id="chyba0"></label>
    <div class="login-wrap">
        <input class="login-input" type="text" name="jmeno" placeholder="<?php echo $lang[2]; ?>">
        <input class="login-input" type="password" name="heslo" placeholder="<?php echo $lang[4]; ?>">
    </div>
    <input class="tryit" type="submit" value="<?php echo $lang[16]; ?>">

    <script type="text/javascript">
        formular_upload("#log", "index.php?post=login", function (data) {
            if (data[0] == 1)
                chyba0 = "<?php echo $lang[7];?>";
            if (data[0] == 2)
                chyba0 = "<?php echo $lang[17];?>";
            if (data[0] == 0) {
                chyba0 = "";
                window.location.href = "./";
            }
            $("#chyba0").text(chyba0);
        });

    </script>

</form>