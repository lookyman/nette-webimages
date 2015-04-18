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
	 * @param string
	 * @param null|string $name
	 * @return mixed ID of saved file with image.
	 */
	public function save($source, $name = null);
}
