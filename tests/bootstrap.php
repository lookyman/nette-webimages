<?php

// The Nette Tester command-line runner can be
// invoked through the command: ../vendor/bin/tester .

if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}


// configure environment
Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');


// create temporary directory
define('TEMP_DIR', __DIR__ . '/tmp/' . getmypid());
@mkdir(dirname(TEMP_DIR)); // @ - directory may already exist
Tester\Helpers::purge(TEMP_DIR);


class Notes
{
	static public $notes = array();

	public static function add($message)
	{
		self::$notes[] = $message;
	}

	public static function fetch()
	{
		$res = self::$notes;
		self::$notes = array();
		return $res;
	}

}


function createContainer(Nette\DI\Compiler $compiler, $file)
{
	$class = 'Container' . md5(lcg_value());
	$loader = new Nette\DI\Config\Loader;
	$config = $loader->load($file);
	$code = $compiler->compile((array)$config, $class, 'Nette\DI\Container');

	file_put_contents(TEMP_DIR . '/code.php', "<?php\n\n$code");
	require TEMP_DIR . '/code.php';
	return new $class;
}
