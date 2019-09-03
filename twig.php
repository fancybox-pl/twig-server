<?php

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFunction;

function getTwig(string $path): Environment
{
    $loader = new FilesystemLoader($path);
    $twig = new Environment($loader);

    $twig->addFunction(new TwigFunction('dump', function ($value) {
        dump($value);
    }));

    return $twig;
}
