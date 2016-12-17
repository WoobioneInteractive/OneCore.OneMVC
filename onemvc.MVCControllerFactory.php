<?php

/**
 * Class MVCControllerFactory
 */
class MVCControllerFactory implements IMVCControllerFactory {

	/**
	 * @var string
	 */
	protected $controllerClassPrefix = '';

	/**
	 * @var string
	 */
	protected $controllerClassSuffix = '';

	/**
	 * Get controller class name
	 * @param string $controller
	 * @return string
	 */
	protected function getControllerClassName($controller) {
		return $this->controllerClassPrefix . $controller . $this->controllerClassSuffix;
	}

	/**
	 * Validate controller class name to see if class exists and matches specified interface
	 * @param string $controllerClassName
	 * @throws MVCControllerFactoryException
	 */
	protected function validateControllerClassName($controllerClassName)
	{
		if (!class_exists($controllerClassName))
			throw new MVCControllerFactoryException("Could not find controller class '$controllerClassName'");

		if (!OnePHP::ClassImplements($controllerClassName, IMVCController::class))
			throw new MVCControllerFactoryException("Class '$controllerClassName' does not implement the required interface '" . IMVCController::class . "'");
	}

	/**
	 * Get instance of controller by class name
	 * @param $controllerClassName
	 * @return IMVCController
	 */
	protected function constructControllerInstance($controllerClassName) {
		$this->validateControllerClassName($controllerClassName);
		return new $controllerClassName;
	}

	/**
	 * Set controller class prefix
	 * @param string $controllerClassPrefix
	 */
	public function SetClassPrefix($controllerClassPrefix) {
		$this->controllerClassPrefix = $controllerClassPrefix;
	}

	/**
	 * Set controller class suffix
	 * @param string $controllerClassSuffix
	 */
	public function SetClassSuffx($controllerClassSuffix) {
		$this->controllerClassSuffix = $controllerClassSuffix;
	}

	/**
	 * @param string $controller
	 * @return IMVCController
	 */
	public function GetController($controller) {
		$controllerClassName = $this->getControllerClassName($controller);
		return $this->constructControllerInstance($controllerClassName);
	}

}

/**
 * Class MVCControllerFactoryException
 */
class MVCControllerFactoryException extends Exception
{
}