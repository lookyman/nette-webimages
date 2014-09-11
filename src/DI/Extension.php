<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages\DI;

use Nette\DI\CompilerExtension;
use Nette\Utils\Validators;


class Extension extends CompilerExtension
{

	/** @var array */
	protected $defaults = array(
		'routes' => array(),
		'rules' => array(),
		'providers' => array(),
		'wwwDir' => '%wwwDir%',
	);


	/** @var array */
	protected $ruleDefaults = array(
		'width' => 0,
		'height' => 0,
		'flags' => NULL,
	);


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$this->validateConfig($this->defaults, $config);

		$validator = $builder->addDefinition($this->prefix('validator'))
			->setClass('DotBlue\WebImages\Validator');

		Validators::assertField($config, 'rules', 'array');

		foreach ($config['rules'] as $name => $rule) {
			Validators::is($rule, 'array');
			$this->validateConfig($this->ruleDefaults, $rule, $this->prefix('rules.' . $name));
			Validators::assertField($rule, 'width', 'int');
			Validators::assertField($rule, 'height', 'int');
			!isset($rule['flags']) || Validators::assertField($rule, 'flags', 'int');

			$builder->addDefinition($this->prefix('rule.' . $name))
				->setClass('DotBlue\WebImages\Rule', array(
					$rule['width'],
					$rule['height'],
					isset($rule['flags']) ? $rule['flags'] : NULL
				))
				->setAutowired(FALSE);

			$validator->addSetup('addRule', array($this->prefix('@rule.' . $name)));
		}

		$builder->addDefinition($this->prefix('generator'))
			->setClass('DotBlue\WebImages\Generator', array($config['wwwDir']));
	}


	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$generator = $builder->getDefinition($this->prefix('generator'));

		Validators::assertField($config, 'providers', 'array');

		foreach ($config['providers'] as $name => $provider) {
			Validators::is($provider, 'string');

			$this->compiler->parseServices($builder, array(
				'services' => array($this->prefix('provider.' . $name) => $provider),
			));
			$generator->addSetup('addProvider', array($this->prefix('@provider.' . $name)));
		}

		$router = $builder->getDefinition('router');

		Validators::assertField($config, 'routes', 'array');

		$i = 0;
		foreach (array_reverse($config['routes']) as $mask => $defaults) {
			if (!is_array($defaults)) {
				$mask = $defaults;
				$defaults = array();
			}

			Validators::is($mask, 'string');
			$this->validateConfig($this->ruleDefaults, $defaults, $this->prefix('routes.' . $i));
			!isset($defaults['width']) || Validators::assertField($defaults, 'width', 'int');
			!isset($defaults['height']) || Validators::assertField($defaults, 'height', 'int');
			!isset($defaults['flags']) || Validators::assertField($defaults, 'flags', 'int');

			$builder->addDefinition($this->prefix('route.' . $i))
				->setClass('DotBlue\WebImages\Application\Route', array(
					$mask,
					$defaults,
					$this->prefix('@generator'),
				))
				->setAutowired(FALSE);

			$router->addSetup('DotBlue\WebImages\Helpers::prependRoute', array(
					'@self',
					$this->prefix('@route.' . $i),
				));

			$i++;
		}

		$latte = $builder->hasDefinition('nette.latteFactory')
			? $builder->getDefinition('nette.latteFactory')
			: $builder->getDefinition('nette.latte');
		$latte->addSetup('DotBlue\WebImages\Latte\Macros::install(?->getCompiler())', array('@self'));
	}


	/**
	 * Checks whether $config contains only $expected items.
	 * @throws \Nette\InvalidStateException
	 */
	protected function validateConfig(array $expected, array $config = NULL, $name = NULL)
	{
		if ($extra = array_diff_key($config !== NULL ? $config : $this->config, $expected)) {
			$name = $name ?: $this->name;
			$extra = implode(", $name.", array_keys($extra));
			throw new \Nette\InvalidStateException("Unknown configuration option $name.$extra.");
		}
	}

}
