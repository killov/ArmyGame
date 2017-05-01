Instalace:
    1. Vytvořit MySQL databázi
    2. Nastavit připojení k databázi v config.php
    3. Spustit instalaci: php -q install.php
    4. V "www/.htaccess" a v "config.php" upravit cestu k webu

Provoz:
    1. Spustit websocket server: php -q bin/ws.php
    2. Spustit AG daemona: php -q bin/daemon.php