<?php
/**
 * @package DotBlue\WebImages
 * @author Ladislav Vondráček <lad.von@gmail.com>
 */

namespace DotBlue\WebImages;

use Nette;

interface IRepository
{
	/**
	 * @param \Nette\Utils\Image $image
	 * @param null|string $name
	 * @param mixed $photoType
	 * @return mixed ID of saved file with image.
	 */
	public function save(Nette\Utils\Image $image, $name = null, $photoType = null);
}
