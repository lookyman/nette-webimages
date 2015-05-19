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

		// POKUD SE NECO POSERE, TENTO KUS KODU ZAKOMENTOVAT A ODKOMENTOVAT PUVODNI KTERY +- FUNGOVAL ASPON NA WEBU, NE V EMAILECH
		$code[] = $writer->write('
		$_presenter->logger->log("info", "MacroSrc - baseUrl: ", [$baseUrl]);
		$imgBaseUrl = rtrim($_presenter->context->parameters["images"]["baseUrl"], "/");
		$_presenter->logger->log("info", "MacroSrc - imgBaseUrl: ", [$imgBaseUrl]);
		$arg = DotBlue\WebImages\Helpers::prepareArguments(%node.array);
		$_presenter->logger->log("info", "MacroSrc - parameters: ", $arg);
		$link = $_presenter->link(":Nette:Micro:", $arg);
		$_presenter->logger->log("info", "MacroSrc - link: ", [$link]);
		$url =  %escape(%modify($imgBaseUrl.$link));
		$_presenter->logger->log("info", "MacroSrc - result url: ", [$url]);
		echo $url;
		');

//		$code[] = $writer->write('$imgBaseUrl = rtrim($_presenter->context->parameters["images"]["baseUrl"], "/");');
//		$code[] = $writer->write('$destination = ($imgBaseUrl == "" ? "//" : "" ) . ":Nette:Micro:";');
//		$code[] = $writer->write('$link = $_presenter->link($destination, DotBlue\WebImages\Helpers::prepareArguments(%node.array));');
//		$code[] = $writer->write('$exp = explode($baseUrl, $link);');
//		$code[] = $writer->write('$relativeLink = "/" . ltrim(array_pop($exp), "/");');
//		$code[] = $writer->write('echo %escape(%modify($imgBaseUrl . $relativeLink));');

		return implode('', $code);
	}

}
