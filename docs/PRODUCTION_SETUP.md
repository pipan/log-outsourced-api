```
mkdir releases
cd releases
composer create-project outsourced/log 1
cd ../

mkdir storage
cp -r releases/1/storage/* storage

mkdir environment
cp releases/1/examples/.env environment/.env
cp releases/1/examples/config.php environment/config.php

mkdir public
cp releases/1/examples/index.php public/index.php
cp releases/1/public/.htaccess public/.htaccess
cp releases/1/public/robots.txt public/robots.txt
cp releases/1/public/web.config public/web.config

mkdir console
cp releases/1/examples/console.php console/artisan

ln -s releases/1 current

```

> be sure that the last comman (ln -s) has finnished

last thing is to change storage permissions `sudo chown -R www-data:www-data storage`