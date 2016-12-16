<?php

class MVCControllerFactory implements  {

	/**
	 * @var callable 
	 */
	private $factoryMethod = null;
	
	/**
	 * @var string
	 */
	private $controllerClassPrefix = '';

	/**
	 * @var string
	 */
	private $controllerClassSuffix = '';

	public function __construct(callable $factoryMethod = null) {
		$this->factoryMethod = $factoryMethod;
	}

	private function getControllerClassName($controllerName) {
		return $this->controllerClassPrefix . $controllerName . $this->controllerClassSuffix;
	}
	
	private function constructControllerInstance($controllerClassName) {
		return new $controllerClassName;
	}

	public function SetClassPrefix($controllerClassPrefix) {
		$this->controllerClassPrefix = $controllerClassPrefix;
	}

	public function SetClassSuffx($controllerClassSuffix) {
		$this->controllerClassSuffix = $controllerClassSuffix;
	}

	public function GetInstance($controllerName) {
		$controllerClassName = $this->getControllerClassName($controllerName);
		if (!is_null($this->factoryMethod))
			$this->factoryMethod($controllerClassName);
		
		return $this->constructControllerInstance($controllerClassName);
	}

}
