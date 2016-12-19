<?php

/**
 * Interface IMVCController
 */
interface IMVCController
{

	/**
	 * @return string
	 */
	public function GetTemplateName();

	/**
	 * @return array
	 */
	public function GetViewBag();

}