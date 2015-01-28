<?php
/**
 * @package DotBlue\WebImages
 * @author Ladislav Vondráček <lad.von@gmail.com>
 */

namespace DotBlue\WebImages;

use Nette;

class Uploader extends Nette\Object
{
	/** @var \DotBlue\WebImages\IRepository[] */
	protected $repositories = [];


	/**
	 * @param \DotBlue\WebImages\IRepository $repository
	 * @return \DotBlue\WebImages\Uploader
	 */
	public function addRepository(IRepository $repository)
	{
		$this->repositories[] = $repository;

		return $this;
	}


	/**
	 * Forwards source of the image to all repositories
	 *
	 * @param \Nette\Http\FileUpload|string $source String may be URL of the image or image encoded in Base64.
	 * @param null|string $name
	 * @return array Paths of all destinations in format.
	 */
	public function uploadImage($source, $name = null)
	{
		$image = $this->getImage($source);
		if (!isset($image)) {
			throw new \InvalidArgumentException('Type of source of the image is not supported.');
		}

		$paths = [];
		foreach ($this->repositories as $repository) {
			$paths[] = $repository->save($image, $name);
		}

		return $paths;
	}


	/**
	 * Returns Image from source
	 *
	 * @param \Nette\Http\FileUpload|string $source
	 * @return \Nette\Utils\Image|null
	 * @throws \Nette\Utils\UnknownImageFileException
	 */
	private function getImage($source)
	{
		$image = null;
		if ($source instanceof Nette\Http\FileUpload) {
			$image = Nette\Utils\Image::fromFile($source->getTemporaryFile());
		}
		elseif (is_string($source)) {
			if ($this->isUrl($source)) {
				$image = Nette\Utils\Image::fromFile($source);
			}
			elseif ($this->isBase64($source)) {
				$image = Nette\Utils\Image::fromString($source);
			}
		}

		return $image;
	}


	/**
	 * Check if is source in base64
	 *
	 * @param string $source
	 * @return bool
	 */
	private function isBase64($source)
	{
		return base64_decode($source) !== false;
	}


	/**
	 * Check if is source the URL
	 *
	 * @param string $source
	 * @return bool
	 */
	private function isUrl($source)
	{
		$pattern = '~\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]~i';

		return preg_match($pattern, $source) === 1;
	}
}
