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
		$code[] = $writer->write('$destination = (empty($imgBaseUrl) ? "//" : "" ) . ":Nette:Micro:";');
		$code[] = $writer->write('$link = $_presenter->link($destination, DotBlue\WebImages\Helpers::prepareArguments(%node.array));');
		$code[] = $writer->write('$stripPos = substr($link, 0, 4) === "http" ? strpos($link, "/", 10) : 0;');
		$code[] = $writer->write('$link = $imgBaseUrl . substr($link, $stripPos);');
		$code[] = $writer->write('echo %escape(%modify($link));');

		return implode('', $code);
	}

}
