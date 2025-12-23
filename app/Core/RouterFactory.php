<?php

declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;

        // Login
        $router->addRoute('login', 'Login:login');
        $router->addRoute('logout', 'Login:logout');

        // Homepage
        $router->addRoute('dashboard', 'User:list');

        // Users actions
        $router->addRoute('users/<action>[/<id>]', 'User:default');

        return $router;
	}
}
