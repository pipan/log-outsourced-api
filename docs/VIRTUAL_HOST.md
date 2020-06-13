# Virtual hosts

This is an example of standard virtual host for this outsourced log application.

## Apache

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

TODO