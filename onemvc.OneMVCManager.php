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
	 * @var string
	 */
	private $rootDirectory = '/';

	/**
	 * @var string
	 */
	private $viewDirectory = 'Views';

	/**
	 * @var string
	 */
	private $baseTemplateName = 'Template';

	/**
	 * OneMVCManager constructor.
	 * @param IMVCControllerFactory|null $controllerFactory
	 */
	public function __construct(IMVCControllerFactory $controllerFactory = null)
	{
		$this->controllerFactory = $controllerFactory ?: new MVCControllerFactory();
	}

	/**
	 * Handle action result
	 * @param $result
	 */
	private function handleResult($result, OneMVCExecutionContext $context)
	{
		// If false - interpret as void page
		if (is_bool($result) && !$result)
			return;

		// Null means get default view
		if (is_null($result))
			$this->LoadView($context->GetActionName(), $context->GetControllerName(), $context->GetTemplateName(), $context);
	}

	/**
	 * Set root directory for your MVC application
	 * @param string $directory
	 */
	public function SetRootDirectory($directory)
	{
		$this->rootDirectory = $directory;
	}

	/**
	 * Set view directory for your MVC application - this is relative to the root directory
	 * @param string $directory
	 */
	public function SetViewDirectory($directory)
	{
		$this->viewDirectory = $directory;
	}

	/**
	 * Set base template
	 * @param $baseTemplateName
	 */
	public function SetBaseTemplate($baseTemplateName)
	{
		$this->baseTemplateName = $baseTemplateName;
	}

	/**
	 * @param string $view
	 * @param string $folder
	 * @param string|null $template
	 * @param OneMVCExecutionContext|null $viewContext
	 * @throws OneMVCException
	 */
	public function LoadView($view, $folder = '/', $template = null, OneMVCExecutionContext $viewContext = null)
	{
		$viewFilePath = trim($this->rootDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->viewDirectory . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $view . OnePHP::PHPFileExtension;
		if (!file_exists($viewFilePath))
			throw new OneMVCException("Could not find view '$view' in folder '$folder'");

		$Context = $viewContext;
		$ViewBag = $viewContext->GetViewBag();

		ob_start();
		require_once $viewFilePath;
		$View = ob_get_contents();
		ob_end_clean();

		if ($template) {
			$templateFilePath = trim($this->rootDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->viewDirectory . DIRECTORY_SEPARATOR . $template . OnePHP::PHPFileExtension;
			if (file_exists($templateFilePath))
				require_once $templateFilePath;
		} else {
			echo $View;
		}
	}

	/**
	 * @param string $controller
	 * @param string $action
	 * @param array $parameters
	 */
	public function Execute($controller, $action, Array $parameters = [])
	{
		$context = new OneMVCExecutionContext($this->controllerFactory, $controller, $action, $parameters);
		$context->SetTemplate($this->baseTemplateName);
		$this->handleResult($context->Execute(), $context);
	}

}

class OneMVCException extends Exception
{
}