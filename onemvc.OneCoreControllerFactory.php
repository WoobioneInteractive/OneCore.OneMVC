<?php

class OneCoreControllerFactory extends MVCControllerFactory
{
	/**
	 * @var DependencyInjector
	 */
	protected $di;

	/**
	 * OneCoreControllerFactory constructor.
	 * @param DependencyInjector $di
	 */
	public function __construct(DependencyInjector $di, IFileAutoLoader $autoLoader)
	{
		$this->di = $di;
	}

	/**
	 * @param string $controllerClassName
	 * @return IMVCController
	 */
	protected function constructControllerInstance($controllerClassName)
	{
		return $this->di->AutoWire($controllerClassName);
	}

}