<?php

abstract class MVCController implements IMVCController {
	
	/**
	 * @var array
	 */
	protected $viewBag = [];
	
	public function __construct($viewBag = []) {
		$this->viewBag = $viewBag;
	}
	
}