# Tiny Twig Semi-Standalone Server

## Instalation

In console:

```
git clone https://bitbucket.org/fancybox/twig-server.git
cd twig-server
composer install
```

## Start

In console: Go into project dir and `php -S 127.0.0.1:8081 router.php`. You can change ip/port at will.

Create your websites in `pages` directory. Rules:

* Every page lives in its dedicated directory.
* Every page is sandoxed.
* `index.html.twig` is loading by default.
* `.html.twig` suffix is added automatically.
* Every page can have `custom.php` file in it's root. It well be loaded just before rendering template. It have access to some variables as well.
