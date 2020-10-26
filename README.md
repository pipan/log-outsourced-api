# Outsourced

[![Build Status](https://travis-ci.com/pipan/log-outsourced-api.svg?branch=master)](https://travis-ci.com/pipan/log-outsourced-api)

![logo](docs/assets/favicon-32x32.png)

Standalone server service for managing your applications's:

* logs
* user permissions

## Demo

[Outsourced web client](https://outsourced.ovaldo.sk)

Create new connection to demo server:

* Name: demo
* Host: https://log-demo-api.ovaldo.sk
* Username: root
* Password: root

![create demo connection](https://media.giphy.com/media/Dl92GpWlF5S8F8Vquk/giphy.gif)

## Installation

### Demo Installation

Demo installlation is good just for trying thinks out. You will not be able to update application in the future easily.

First step is to install source code

`composer create-project --prefer-dist --no-dev outsourced/log`

> this command will create a folder named `log`. This guide will assume, you have not changed this folder name

Switch to newly creted directory `cd log` and start app setup.

```
php artisan setup
php artisan setup:database
php artisan migrate
```

These command will guide you trough app setup, database setup and will create all database tables.

`sudo chown -R www-data:www-data storage`

This commad will grant access permission for storage folder. Application logs are stored there.

Then you have to configure your [virtual host](docs/VIRTUAL_HOST.md).

> If you are runnig this application localy, then you will have to add <your_domain> to `hosts` file.

Now you should be able to access one API endpoint. Open your web browser and input `<subdomain>.<your_domain>/api/v1/projects`. You should receive json response with empty array.

### Installation

Production installation is slightlly different, because you have to think about future updates. That requires a special directory structure. We have prepared a [list of commands](docs/PRODUCTION_SETUP.md) that will create this directory structure for you. The idea is to have a `releases` directory, that contains every update. You can switch what version is currenctly in use. To switch between version you will use direcotry link `current` that will point tu currently used version. Then there is a `storage` direcotry, that stores your application data an logs. So every version in releases directory will use the same storage. `environment` directory has configuration for this instance of application. `public` directory contains `index.php` and `.htaccess` files. This is also a directory that your virtual host should refer to. And last directory is `console` and there you can find script that can help you set up some enviroment values or clear cache or migrate database ...

Then you should go to `console` directory and run initial setup.

```
cd console
php artisan key:generate --ansi
php artisan init:file
php artisan setup
php artisan setup:database
php artisan migrate
cd ../
```

Application will need access to one directory, so you will have to run `sudo chown -R www-data:www-data storage`

Then you have to configure your [virtual host](docs/VIRTUAL_HOST.md). And add ertificate. For testing purpose, we created tutorial [how to setup certificate with certbot](docs/CERT.md)

> If you are runnig this application localy, then you will have to add <your_domain> to `hosts` file.

Now you should be able to access one API endpoint. Open your web browser and input `<subdomain>.<your_domain>/api/v1/projects`. You should receive json response with empty array.

### Update to new version

```
cd console
php artisan release:upgrade
cd ../
```

### Rollback to previous version

```
cd console
php artisan release:rollback
cd ../
```

## API

## Contribution