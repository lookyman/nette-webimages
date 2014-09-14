<?php

/**
 * Test: DotBlue\WebImages\DI\Extension invalid config
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, "
webimages:
    extra: TRUE
includes:
    - 'files/Config.common.neon'
");
}, 'Nette\InvalidStateException', "Unknown configuration option webimages.extra.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, __DIR__ . '/files/Extension.invalid.02.neon');
}, 'Nette\Utils\AssertionException', "The option webimages.rules expects to be array, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, __DIR__ . '/files/Extension.invalid.03.neon');
}, 'Nette\Utils\AssertionException', "The option webimages.rules.0 expects to be array, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, __DIR__ . '/files/Extension.invalid.04.neon');
}, 'Nette\InvalidStateException', "Unknown configuration option webimages.rules.0.extra.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, __DIR__ . '/files/Extension.invalid.05.neon');
}, 'Nette\Utils\AssertionException', "Missing option webimages.rules.0.width.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, __DIR__ . '/files/Extension.invalid.06.neon');
}, 'Nette\Utils\AssertionException', "The option webimages.rules.0.width expects to be int, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, __DIR__ . '/files/Extension.invalid.07.neon');
}, 'Nette\Utils\AssertionException', "Missing option webimages.rules.0.height.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, __DIR__ . '/files/Extension.invalid.08.neon');
}, 'Nette\Utils\AssertionException', "The option webimages.rules.0.height expects to be int, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, __DIR__ . '/files/Extension.invalid.09.neon');
}, 'Nette\Utils\AssertionException', "The option webimages.rules.0.flags expects to be int, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, __DIR__ . '/files/Extension.invalid.10.neon');
}, 'Nette\Utils\AssertionException', "The option webimages.providers expects to be array, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, __DIR__ . '/files/Extension.invalid.11.neon');
}, 'Nette\Utils\AssertionException', "The option webimages.providers.0 expects to be string, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, __DIR__ . '/files/Extension.invalid.12.neon');
}, 'Nette\Utils\AssertionException', "The option webimages.routes expects to be array, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, __DIR__ . '/files/Extension.invalid.13.neon');
}, 'Nette\Utils\AssertionException', "The option webimages.routes.0 expects to be string, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, __DIR__ . '/files/Extension.invalid.14.neon');
}, 'Nette\InvalidStateException', "Unknown configuration option webimages.routes.0.extra.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, __DIR__ . '/files/Extension.invalid.15.neon');
}, 'Nette\Utils\AssertionException', "The option webimages.routes.0.width expects to be int, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, __DIR__ . '/files/Extension.invalid.16.neon');
}, 'Nette\Utils\AssertionException', "The option webimages.routes.0.height expects to be int, boolean given.");

Assert::exception(function() {
	$compiler = new Nette\DI\Compiler;
	$compiler->addExtension('webimages', new DotBlue\WebImages\DI\Extension);
	createContainer($compiler, __DIR__ . '/files/Extension.invalid.17.neon');
}, 'Nette\Utils\AssertionException', "The option webimages.routes.0.flags expects to be int, boolean given.");
