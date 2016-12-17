<?php

/**
 * Class OneMVCManager
 */
class OneMVCManager {

	/**
	 * @var IMVCControllerFactory
	 */
	private $controllerFactory;

	/**
	 * OneMVCManager constructor.
	 * @param IMVCControllerFactory|null $controllerFactory
	 */
	public function __construct(IMVCControllerFactory $controllerFactory = null)
	{
		$this->controllerFactory = $controllerFactory ?: new MVCControllerFactory();
	}

	/**
	 * @param string $controller
	 * @param string $action
	 * @param array $parameters
	 */
	public function Execute($controller, $action, Array $parameters = [])
	{
		$controllerInstance = $this->controllerFactory->GetController($controller);
		$actionReflection = new ReflectionMethod($controllerInstance, $action);
		$actionParameters = $actionReflection->getParameters();
		$executionParameters = [];
		foreach ($actionParameters as $parameter) {
			$parameterName = $parameter->getName();
			if (array_key_exists($parameterName, $parameters))
				array_push($executionParameters, $parameters[$parameterName]);
		}

		call_user_func_array([$controllerInstance, $action], $executionParameters);
	}

}