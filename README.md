Coupé, Fast HTTP/HTTPS server for PHP developers
================================================

* Non-blocking IO with coroutine (Generator) available in PHP5.5
* Based on [kzykhys/CoroutineIO][coroutine-io]
* Tested against wordpress, drupal(symfony) and Magento(zend framework)

![Terminal](http://kzykhys.com/coupe/assets/img/terminal.png)

Requirements
------------

* PHP5.5 (both CLI and CGI)
* OpenSSL http://www.php.net/manual/en/openssl.installation.php

Installation
------------

```
curl -s http://kzykhys.com/coupe/install | php
```

Or download [coupe.phar][coupe-phar]

Usage
-----

```
php coupe.phar start
```

```
Coupé HTTP Server (dev-master)
Started at Tue, 26 Nov 2013 12:51:18 +0900
Listening on http://localhost:8080
Listening on https://localhost:8443
Press Ctrl-C to quit

[2013/11/26 12:51:21] 404 GET / HTTP/1.1
 "(no referrer)" "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:24.0) Gecko/20100101 Firefox/24.0"
[2013/11/26 12:51:34] 200 GET /composer.json HTTP/1.1
 "(no referrer)" "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:24.0) Gecko/20100101 Firefox/24.0"
```

License
-------

The MIT License

Author
------

Kazuyuki Hayashi (@kzykhys)

[coroutine-io]: https://github.com/kzykhys/CoroutineIO
[coupe-phar]: http://kzykhys.com/coupe/coupe.phar