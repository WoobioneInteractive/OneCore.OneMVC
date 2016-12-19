<?php

abstract class MVCController implements IMVCController
{
	/**
	 * @var string
	 */
	protected $template;

	/**
	 * @var array
	 */
	protected $viewBag = [];

	/**
	 * @return string
	 */
	public function GetTemplateName()
	{
		return $this->template;
	}

	/**
	 * @return array
	 */
	public function GetViewBag()
	{
		return $this->viewBag;
	}

}