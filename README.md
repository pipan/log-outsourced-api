# Log Outsourced

[![Build Status](https://travis-ci.com/pipan/log-outsourced-api.svg?branch=master)](https://travis-ci.com/pipan/log-outsourced-api)

Proxy server for applications logs

## Installation

First step of the installation is to install source code

`composer create-project outsourced/log`

> this command will create a folder named `log`. This guide will assume, you have not changed this folder name

Switch to newly creted directory `cd log` and start setup `php artisan setup`. This setup is interactive, so follow the instructions and fill in required values. Application will need access to one directory, so you will have to run `sudo chown -R www-data:www-data storage`

Then you have to configure your virtual host. We will guide you trough apache configuration. Create new virtual host file for this application `sudo vim /etc/apache2/sites-available/<your_doamin>.conf`.

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

> If you are runnig this application localy, then you will have to add <your_domain> to `hosts` file.

Now you should be able to access one API endpoint. Open your web browser and input `<subdomain>.<your_domain>/api/v1/projects`. You should receive json response with empty array.

## API

## Contribution