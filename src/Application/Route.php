<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages\Application;

use Nette\Application\Routers\Route as BaseRoute;
use Nette\Utils\Image;
use DotBlue\WebImages\Generator;


class Route extends BaseRoute
{

	/** @var array */
	protected $defaults = array(
		'flags' => Image::JPEG
	);


	/**
	 * @param string
	 * @param array
	 * @param \DotBlue\WebImages\Generator
	 */
	public function __construct($mask, array $defaults, Generator $generator)
	{
		$this->defaults = array_replace($this->defaults, $defaults);
		$defaults['presenter'] = 'Nette:Micro';
		$me = $this;

		$defaults[NULL][self::FILTER_OUT] = function($params) use ($me, $generator) {
			$width = $me->acquireArgument('width', $params);
			$height = $me->acquireArgument('height', $params);
			$flags = $me->acquireArgument('flags', $params);

			if (!$generator->getValidator()->validate($width, $height, $flags)) {
				throw new \Nette\Application\ForbiddenRequestException("Image with params ({$width}x{$height}, {$flags}) is not allowed - check your 'webimages.rules' please.");
			}

			return $params;
		};

		$defaults['callback'] = function($presenter) use ($me, $generator) {
			$params = $presenter->getRequest()->getParameters();

			$image = NULL;
			try {
				$id = $me->acquireArgument('id', $params);
				$width = $me->acquireArgument('width', $params);
				$height = $me->acquireArgument('height', $params);
				$flags = $me->acquireArgument('flags', $params);

				$image = $generator->generateImage($id, $width, $height, $flags);
			} catch (\Exception $e) {}

			return new Response($image);
		};

		parent::__construct($mask, $defaults);
	}


	/**
	 * @param string
	 * @param array
	 * @return mixed
	 * @throws \Nette\InvalidStateException
	 */
	public function acquireArgument($name, array $data)
	{
		if (isset($data[$name])) {
			return $data[$name];
		} elseif (isset($this->defaults[$name])) {
			return $this->defaults[$name];
		} else {
			throw new \Nette\InvalidArgumentException("Missing parameter $name.");
		}
	}

}
