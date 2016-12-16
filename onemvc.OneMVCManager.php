<?php

class OneMVCManager {
	
	/**
	 * @var IMVCControllerFactory
	 */
	private $controllerFactory;
	
	public function __construct(IMVCControllerFactory $controllerFactory) {
		$this->controllerFactory = $controllerFactory;
	}
	
}