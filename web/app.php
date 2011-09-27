<?php

$_SERVER['REQUEST_URI'] = (empty($_SERVER['REDIRECT_URL']) ? $_SERVER['REQUEST_URI'] : $_SERVER['REDIRECT_URL']);

require_once __DIR__.'/../app/bootstrap_cache.php.cache';
require_once __DIR__.'/../app/AppCache.php';

use Symfony\Component\HttpFoundation\Request;

$kernel = new AppCache(new AppKernel('prod', false));
//$kernel = new AppKernel('prod', false);
$kernel->handle(Request::createFromGlobals())->send();