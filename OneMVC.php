<?php

class OneMVC {
	
	/**
	 * @var OneMVCManager
	 */
	private $manager;
	
	/**
	 * @var IConfigHandler
	 */
	private $configuration;
	
	
	public function __construct(IConfigHandler $configuration) {
		$this->manager = new OneMVCManager();
	}
	
	public function Execute($controller, $action, $parameters = []) {
		$this->manager->Execute($controller, $action, $parameters);
	}
	
}