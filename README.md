Coupé, A Handy HTTP/HTTPS Server written in *PURE* PHP
======================================================

[![Latest Unstable Version](https://poser.pugx.org/kzykhys/coupe/v/unstable.png)](https://packagist.org/packages/kzykhys/coupe)
[![Build Status](https://travis-ci.org/kzykhys/coupe.png?branch=master)](https://travis-ci.org/kzykhys/coupe)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/25731f48-72cc-4e7c-9100-19267dd91b1b/mini.png)](https://insight.sensiolabs.com/projects/25731f48-72cc-4e7c-9100-19267dd91b1b)

* Non-blocking IO with coroutine (Generator) available in PHP5.5
* Based on [kzykhys/CoroutineIO][coroutine-io]
* Tested against Wordpress, Symfony2

![Terminal](http://kzykhys.com/coupe/assets/img/terminal.png?v=2)

Requirements
------------

* PHP5.5 (both CLI and CGI)
* (optional) OpenSSL http://www.php.net/manual/en/openssl.installation.php

Installation
------------

### On Unix

```
curl -s http://kzykhys.com/coupe/install | php
```

### On Windows

Download [coupe.phar][coupe-phar]

### Update

```
php coupe.phar self-update
```

Usage
-----

Visit <http://kzykhys.com/coupe> for more information.

```
php coupe.phar help start
```

```
Usage:
 start [-t|--docroot="..."] [-s|--with-ssl[="..."]] [--without-ssl] [--fallback="..."] [address]

Arguments:
 address               <host>:<port> (default: "localhost:8080")

Options:
 --docroot (-t)        Specify document root
 --with-ssl (-s)       <host>:<port> (default: "localhost:8443")
 --without-ssl         Disables ssl transport
 --fallback            Fallback script (default: false)
 --help (-h)           Display this help message.
 --quiet (-q)          Do not output any message.
 --verbose (-v|vv|vvv) Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
 --version (-V)        Display this application version.
 --ansi                Force ANSI output.
 --no-ansi             Disable ANSI output.
 --no-interaction (-n) Do not ask any interactive question.
```

License
-------

The MIT License

Author
------

Kazuyuki Hayashi (@kzykhys)

[coroutine-io]: https://github.com/kzykhys/CoroutineIO
[coupe-phar]: http://kzykhys.com/coupe/coupe.phar