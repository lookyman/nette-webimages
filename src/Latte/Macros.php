<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages\Latte;

use Latte\Macros\MacroSet;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\PhpWriter;


class Macros extends MacroSet
{

	public static function install(Compiler $parser)
	{
		$me = new static($parser);
		$me->addMacro('src', function (MacroNode $node, PhpWriter $writer) use ($me) {
			return $me->macroSrc($node, $writer);
		}, NULL, function(MacroNode $node, PhpWriter $writer) use ($me) {
			return ' ?> src="<?php ' . $me->macroSrc($node, $writer) . ' ?>"<?php ';
		});
	}


	/********************* macros ****************v*d**/

	public function macroSrc(MacroNode $node, PhpWriter $writer)
	{
		$code = [];
		$code[] = $writer->write('$imgBaseUrl = rtrim($_presenter->context->parameters["images"]["baseUrl"], "/");');
		$code[] = $writer->write('$destination = ($imgBaseUrl == "" ? "//" : "" ) . ":Nette:Micro:";');
		$code[] = $writer->write('$link = $_presenter->link($destination, DotBlue\WebImages\Helpers::prepareArguments(%node.array));');
		$code[] = $writer->write('$exp = explode($baseUrl, $link);');
		$code[] = $writer->write('$relativeLink = "/" . ltrim(array_pop($exp), "/");');
		$code[] = $writer->write('echo %escape(%modify($imgBaseUrl . $relativeLink));');

		return implode('', $code);
	}

}
