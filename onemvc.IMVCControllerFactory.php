<?php

interface IMVCControllerFactory
{
	/**
	 * Get controller instance
	 * @param $controllerName
	 * @return IMVCController
	 */
	public function GetController($controllerName);

}