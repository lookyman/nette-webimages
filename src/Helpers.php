<?php

namespace DotBlue\WebImages;

use Nette\Application\IRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\Utils\Image;

class Helpers
{

	/**
	 * @throws \Nette\InvalidArgumentException
	 */
	public static function prependRoute(IRouter &$router, Route $route)
	{
		if (!$router instanceof RouteList) {
			throw new \Nette\InvalidArgumentException("Router must be an instance of Nette\\Application\\Routers\\RouteList.");
		}

		$router[] = $route;

		$lastKey = count($router) - 1;
		foreach ($router as $i => $r) {
			if ($i === $lastKey) {
				break;
			}
			$router[$i + 1] = $r;
		}

		$router[0] = $route;
	}


	public static function prepareArguments(array $arguments)
	{
		foreach ($arguments as $key => $value) {
			if ($key === 0 && !isset($arguments['id'])) {
				$arguments['id'] = $value ?: null;
				$arguments['prefixDir'] = self::getPrefix($value);
				unset($arguments[$key]);
			} elseif ($key === 1 && !isset($arguments['width'])) {
				$arguments['width'] = $value;
				unset($arguments[$key]);
			} elseif ($key === 2 && !isset($arguments['height'])) {
				$arguments['height'] = $value;
				unset($arguments[$key]);
			} elseif ($key === 3 && !isset($arguments['flags'])) {
				$arguments['flags'] = $value;
				unset($arguments[$key]);
			}
		}

		if (!isset($arguments['width'])) {
			$arguments['width'] = 0;
		}
		if (!isset($arguments['height'])) {
			$arguments['height'] = 0;
		}
		if (!isset($arguments['flags'])) {
			$arguments['flags'] = Image::FIT;
		}

		return $arguments;
	}


	/**
	 * @param string $name
	 * @return string
	 */
	public static function getPrefix($name)
	{
		return substr($name, 0, 2);
	}

}
