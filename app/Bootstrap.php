<?php

declare(strict_types=1);

namespace App;

use Nette;
use Nette\Bootstrap\Configurator;


class Bootstrap
{
	private Configurator $configurator;
	private string $rootDir;


	public function __construct()
	{
		$this->rootDir = dirname(__DIR__);
		$this->configurator = new Configurator;

		$this->configurator->addDynamicParameters([
			'env' => getenv(),
		]);

		# DOCKER temp directory
		$this->configurator->setTempDirectory('/tmp/nette-temp');

		if (!defined('MYSQLI_TYPE_INTERVAL')) {
			define('MYSQLI_TYPE_INTERVAL', 247);
		}
	}


	public function bootWebApplication(): Nette\DI\Container
	{
		$this->initializeEnvironment();
		$this->setupContainer();
		return $this->configurator->createContainer();
	}


	public function initializeEnvironment(): void
	{
		# DEBUG MODE
		$this->configurator->setDebugMode(true);

		# DOCKER temp log directory
		if ($this->configurator->isDebugMode()) {
			$this->configurator->enableTracy('/tmp/nette-temp');

			if (PHP_SAPI === 'cli') {
				\Tracy\Debugger::$strictMode = false;
			}
		}

		$this->configurator->createRobotLoader()
			->addDirectory($this->rootDir)
			->register();
	}


	private function setupContainer(): void
	{
		$configDir = $this->rootDir . '/config';
		$this->configurator->addConfig($configDir . '/common.neon');
		$this->configurator->addConfig($configDir . '/services.neon');	

		# ENVIRONMENT config
		$env = getenv('APP_ENV') ?: 'development';
		$envConfig = $configDir . '/' . $env . '.neon';
	
		if (is_file($envConfig)) {
			$this->configurator->addConfig($envConfig);
		}
	}
}
