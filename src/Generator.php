<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages;

use Nette;
use Nette\Http\IRequest;
use DotBlue\WebImages\Validator;
use Nette\Utils\Image;


/**
 * @method \DotBlue\WebImages\Validator getValidator()
 * @method \DotBlue\WebImages\IProvider[] getProviders()
 */
class Generator extends Nette\Object
{

	/** @var \Nette\Http\IRequest */
	protected $httpRequest;


	/** @var \DotBlue\WebImages\Validator */
	protected $validator;


	/** @var \DotBlue\WebImages\IProvider[] */
	protected $providers = array();


	/**
	 * @param \Nette\Http\IRequest
	 * @param \DotBlue\WebImages\Validator
	 */
	public function __construct(IRequest $httpRequest, Validator $validator)
	{
		$this->httpRequest = $httpRequest;
		$this->validator = $validator;
	}


	/**
	 * @param \DotBlue\WebImages\IProvider
	 * @return \DotBlue\WebImages\Generator
	 */
	public function addProvider(IProvider $provider)
	{
		$this->providers[] = $provider;
		return $this;
	}


	/**
	 * @param string
	 * @param int
	 * @param int
	 * @param int|NULL
	 * @return \Nette\Utils\Image|NULL
	 * @throws \Exception
	 */
	public function generateImage($id, $width, $height, $flags = NULL)
	{
		if (!$this->validator->validate($width, $height, $flags)) {
			throw new \Exception("Image with params ({$width}x{$height}, {$flags}) is not allowed - check your 'webimages.rules' please.");
		}

		$image = NULL;
		foreach ($this->providers as $provider) {
			$image = $provider->getImage($id, $width, $height, $flags);
			if ($image instanceof Image) {
				break;
			}
		}

		if (!$image instanceof Image) {
			throw new \Exception("Image not found.");
		}

		return $image;
	}


	/**
	 * Returns link to image
	 *
	 * @param \Nette\Application\IPresenter $_presenter Presenter is required for evaluate the code.
	 * @param string $imageName
	 * @param null|int $width
	 * @param null|int $height
	 * @param null|int $flags
	 * @return string
	 */
	public static function link(Nette\Application\IPresenter $_presenter, $imageName, $width = null, $height = null, $flags = null)
	{
		$parametersCode = sprintf('["%s", %s, %s, %s]', $imageName, $width ?: 'null', $height ?: 'null', $flags ?: 'null');

		$code = Latte\Macros::getCode($parametersCode);

		// in this method must be result returned
		$code[] = 'return $link;';

		// code for macro is as string for eval, also here must be eval
		return eval(implode('', $code));
	}



}
