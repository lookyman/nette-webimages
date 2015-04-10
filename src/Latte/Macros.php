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
		$baseUrl = $writer->write('$baseUrl = trim($_presenter->context->parameters["images"]["baseUrl"], "/");');
		$link = $writer->write('$link = ($baseUrl == "" ? "//" : "" ) . ":Nette:Micro:";');
		$echo = $writer->write('echo %escape(%modify($baseUrl . $_presenter->link($link, DotBlue\WebImages\Helpers::prepareArguments(%node.array))))');

		return $baseUrl . $link . $echo;
	}

}
