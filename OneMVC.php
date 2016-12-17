<?php

class OneMVC implements IPlugin
{
	/**
	 * @var IConfiguration
	 */
	private $configuration;

	/**
	 * @var DependencyInjector
	 */
	private $di;

	/**
	 * @var IFileAutoLoader
	 */
	private $fileAutoLoader;

	/**
	 * @var IPluginLoader
	 */
	private $pluginLoader;

	/**
	 * @var OneMVCManager
	 */
	private $manager;

	public function __construct(IConfiguration $configuration, DependencyInjector $di, IFileAutoLoader $fileAutoLoader, IPluginLoader $pluginLoader)
	{
		$this->configuration = $configuration;
		$this->di = $di;
		$this->fileAutoLoader = $fileAutoLoader;
		$this->pluginLoader = $pluginLoader;

		$this->registerDependencies();

		$this->manager = $di->AutoWire(OneMVCManager::class);
	}

	private function registerDependencies()
	{
		$this->di->AddMapping(new DependencyMappingFromArray([
			IMVCControllerFactory::class => [
				DependencyInjector::Mapping_RemoteInstance => function(DependencyInjector $di) {
					/* @var $factory OneCoreControllerFactory */
					$factory = $di->AutoWire(OneCoreControllerFactory::class);
					$factory->SetClassSuffx('Controller');
					return $factory;
				}
			]
		]));
	}

	public function Execute($controller, $action, $parameters = [])
	{
		// TODO Should use autoloader - but autoloader needs to be more powerful first
		require_once $this->pluginLoader->GetApplicationDirectory() . 'Controllers/controller.' . $controller . OnePHP::PHPFileExtension;
		$this->manager->Execute($controller, $action, $parameters);
	}

}