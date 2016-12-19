<?php

class OneMVCExecutionContext
{
	/**
	 * @var IMVCControllerFactory
	 */
	private $controllerFactory;

	/**
	 * @var IMVCController
	 */
	private $controller;

	/**
	 * @var string
	 */
	private $controllerName;

	/**
	 * @var string
	 */
	private $actionName;

	/**
	 * @var array
	 */
	private $parameters;

	/**
	 * String template name or bool false for no template
	 * @var string|bool
	 */
	private $templateName;

	/**
	 * OneMVCExecutionContext constructor.
	 * @param $controllerFactory
	 * @param $controllerName
	 * @param $actionName
	 * @param $parameters
	 */
	public function __construct($controllerFactory, $controllerName, $actionName, $parameters)
	{
		$this->controllerFactory = $controllerFactory;
		$this->controllerName = $controllerName;
		$this->actionName = $actionName;
		$this->parameters = $parameters;
	}

	/**
	 * @return IMVCController
	 */
	public function GetController()
	{
		return $this->controller;
	}

	/**
	 * @return string
	 */
	public function GetControllerName()
	{
		return $this->controllerName;
	}

	/**
	 * @return string
	 */
	public function GetActionName()
	{
		return $this->actionName;
	}

	/**
	 * @return string
	 */
	public function GetTemplateName()
	{
		return $this->templateName;
	}

	/**
	 * @param string $templateName
	 */
	public function SetTemplate($templateName)
	{
		$this->templateName = $templateName;
	}

	/**
	 * @return array
	 */
	public function GetViewBag()
	{
		return $this->controller->GetViewBag();
	}

	/**
	 * Execute context
	 * @return mixed
	 */
	public function Execute()
	{
		if (!$this->controller) {
			$this->controller = $this->controllerFactory->GetController($this->controllerName);

			// Handle templating
			$templateName = $this->controller->GetTemplateName();
			if (!is_null($templateName))
				$this->templateName = $templateName;

			// Run action
			$actionReflection = new ReflectionMethod($this->controller, $this->actionName);
			$actionParameters = $actionReflection->getParameters();
			$executionParameters = [];
			foreach ($actionParameters as $parameter) {
				$parameterName = $parameter->getName();
				if (array_key_exists($parameterName, $this->parameters))
					array_push($executionParameters, $this->parameters[$parameterName]);
			}

			return call_user_func_array([$this->controller, $this->actionName], $executionParameters);
		}
	}
}