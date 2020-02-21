
ZF App
==================================================

A simple application with Zend Framework 3

## Installation

You need to have Apache 2.4 HTTP server, PHP v.5.6 or later and MySQL v.5.6 or later.

Download the sample to some directory (it can be your home dir or `/var/www/html`) and run Composer as follows:

```
php composer.phar install
```

The command above will install the dependencies (Zend Framework and Doctrine).

Enable development mode:

```
php composer.phar development-enable
```

Adjust permissions for `data` directory:

```
sudo chown -R www-data:www-data data
sudo chmod -R 775 data
```

Create `config/autoload/local.php` config file by copying its distrib version:

```
cp config/autoload/local.php.dist config/autoload/local.php
```

Edit `config/autoload/local.php` and set database password parameter.

## Create Database

Login to MySQL client:

```
mysql -u root -p
```

Create database:

```
CREATE DATABASE zf-app;
GRANT ALL PRIVILEGES ON zf-app.* TO user@localhost identified by '<your_password>';
quit
```

Create table and add some dummy data:

```
mysql -u user -p zf-app < data/schema.mysql.sql
```

Alternatively, and if you don't need dummy data,  you can run database migrations:

```
./vendor/bin/doctrine-module migrations:migrate
```
## Start server

You can start the serve with composer.


The app will run at http://0.0.0.0:8787. Goto to composer to change the default port.
If 8787 port is not available, you con execute the following command setting the port.

```
php -S 0.0.0.0:8788 -t public/ public/index.php
```


## License

This code is provided under the [BSD-like license](https://en.wikipedia.org/wiki/BSD_licenses). 
