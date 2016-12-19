<?php

class OneMVC implements IPlugin
{
	const Config_BaseTemplate = 'onemvc.baseTemplate';

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

	/**
	 * OneMVC constructor.
	 * @param IConfiguration $configuration
	 * @param DependencyInjector $di
	 * @param IFileAutoLoader $fileAutoLoader
	 * @param IPluginLoader $pluginLoader
	 */
	public function __construct(IConfiguration $configuration, DependencyInjector $di, IFileAutoLoader $fileAutoLoader, IPluginLoader $pluginLoader)
	{
		$this->configuration = $configuration;
		$this->di = $di;
		$this->fileAutoLoader = $fileAutoLoader;
		$this->pluginLoader = $pluginLoader;

		$this->registerDependencies();

		$this->manager = $di->AutoWire(OneMVCManager::class);
		$this->manager->SetRootDirectory($this->pluginLoader->GetApplicationDirectory());

		// Set base template from config
		$baseTemplate = $this->configuration->Get(self::Config_BaseTemplate);
		if ($baseTemplate)
			$this->manager->SetBaseTemplate($baseTemplate);
	}

	/**
	 * Register dependencies
	 */
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

	/**
	 * Execute MVC
	 * @param $controller
	 * @param $action
	 * @param array $parameters
	 */
	public function Execute($controller, $action, $parameters = [])
	{
		// TODO Should use autoloader - but autoloader needs to be more powerful first
		require_once $this->pluginLoader->GetApplicationDirectory() . 'Controllers/controller.' . $controller . OnePHP::PHPFileExtension;
		$this->manager->Execute($controller, $action, $parameters);
	}

}