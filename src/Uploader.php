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
		$sourcePath = $this->getSourcePath($source);

		if (!isset($sourcePath)) {
			$msg = sprintf('Type of source of the image is not supported. Given: %s', var_export($source, true));
			throw new \InvalidArgumentException($msg);
		}

		$paths = [];
		foreach ($this->repositories as $repository) {
			$paths[] = $repository->save($sourcePath, $name);
		}

		return $paths;
	}


	/**
	 * Returns path to file source
	 *
	 * @param \Nette\Http\FileUpload|string $source
	 * @return null|string
	 */
	private function getSourcePath($source)
	{
		$sourcePath = null;
		if ($source instanceof Nette\Http\FileUpload) {

			$sourcePath = $source->getTemporaryFile();
		}
		elseif (is_string($source) && (Nette\Utils\Validators::isUrl($source) || file_exists($source))) {
			$sourcePath = $source;
		}

		return $sourcePath;
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
		return Nette\Utils\Validators::isUrl($source);
	}
}
