# Virtual hosts

This is an example of standard virtual host for this outsourced log application.

## Apache

You will have to enable 2 mods `headers` and `rewrite`

```
a2enmod headers
a2enmod rewrite
sudo service apache2 restart
```

If you are using apache and linux, then command to create new virtual host is `sudo vim /etc/apache2/sites-available/<your_doamin>.conf`.

Copy, paste and edit this virtual host.

```
<VirtualHost *:80>
    DocumentRoot "/path/to/outsourced/log/installation/folder/public"
    ServerName <your_domain>
    ServerAlias <subdomain>.<your_domain>
    <Directory "/path/to/outsourced/log/installation/folder">
        AllowOverride All
        Require all granted
        Header set Access-Control-Allow-Origin "*"
	    Header set Access-Control-Allow-Headers "content-type"
        Header set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"
    </Directory>
</VirtualHost>
```

Enable new virtual host `sudo a2ensite <your_domain>` and restart apache `sudo service apache2 restart`.

## Nginx

```
server {
    listen 80;
    listen [::]:80;

    root /path/to/outsourced/log/installation/folder/public;

    index index.php;
    server_name <subdomain>.<your_domain>;

    location ^~ /.well-known {
        allow all;
    }

    add_header 'Access-Control-Allow-Origin' '*' always;
    add_header 'Access-Control-Allow-Headers' 'Content-Type' always;
    add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, DELETE, OPTIONS' always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php7.2-fpm.sock;
    }

    # SSL
    # listen 443 ssl;
    # ssl_certificate /etc/letsencrypt/live/<subdomain>.<your_domain>/fullchain.pem;
    # ssl_certificate_key /etc/letsencrypt/live/<subdomain>.<your_domain>/privkey.pem;
    # include /etc/letsencrypt/options-ssl-nginx.conf;

    # if ($scheme != "https") {
        # return 301 https://$host$request_uri;
    # }
}
```

Enable new virtual host `ln -s /etc/nginx/sites-available/<subdomain>.<your_domain> /etc/nginx/sites-enabled/<subdomain>.<your_domain>` and restart nginx `sudo service nginx restart`.