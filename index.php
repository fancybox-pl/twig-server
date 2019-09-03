<?php

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/twig.php';

$uri = $_SERVER['REQUEST_URI'];
$fullResourcePath = buildFullResourcePath($uri);

if (!$fullResourcePath) {
    header("HTTP/1.0 404 Not Found");
    die('404');
}

$rootDirectory = getRootDirectory($fullResourcePath);
$twig = getTwig("pages/$rootDirectory");

$resourcePath = getResourcePath($fullResourcePath);
if (false !== mb_strpos($resourcePath, '.twig')) {
    $params = [
        'fullResourcePath' => $fullResourcePath,
        'rootDirectory' => $rootDirectory,
        'resourcePath' => $resourcePath,
    ];

    $globalsPath = "pages/$rootDirectory/custom.php";

    if (file_exists($globalsPath)) {
        include $globalsPath;
    }

    return $twig->display($resourcePath, $params);
}

$handler = fopen($fullResourcePath, 'rb');
header('Content-Type: ' . mime_content_type($fullResourcePath));
header('Content-Length: ' . filesize($fullResourcePath));

fpassthru($handler);
exit;

function buildFullResourcePath(string $uri): ?string
{
    $path = rtrim('pages'.$uri, '/');

    // If this is dir - fetch index.html.twig from it
    if (file_exists($path) && is_dir($path)) {
        $path = $path.'/index.html.twig';

        if (!file_exists($path)) {
            return null;
        }

        return $path;
    }

    // If there is no dot in filename - check if such twig template exists
    if (false === mb_strpos($path, '.')) {
        $twigPath = $path.'.html.twig';

        if (file_exists($twigPath)) {
            return $twigPath;
        }
    }

    // If file exists - return it as is
    if (file_exists($path)) {
        return $path;
    }

    return null;
}

function getRootDirectory(string $fullResourcePath): ?string
{
    $parts = explode('/', $fullResourcePath);
    $parts = array_slice($parts, 1);
    $parts = array_slice($parts, 0, -1);

    if (!count($parts)) {
        return null;
    }

    return reset($parts);
}

function getResourcePath(string $fullResourcePath): ?string
{
    $parts = explode('/', $fullResourcePath);
    $parts = array_slice($parts, 2);

    return implode('/', $parts);
}
