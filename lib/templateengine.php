<?php
namespace Wlbl\Twigrix;

use Bitrix\Main\Application;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;

class TemplateEngine
{
	/**
	 * Окружение twig
	 *
	 * @var \Twig_Environment
	 */
	private $environment;

	private static $instance = null;

	/**
	 * @return \Twig_Environment
	 */
	public function getEnvironment()
	{
		return $this->environment;
	}

	private function __construct()
	{
		// Initialize Twig template engine
		$documentRoot = Application::getDocumentRoot();
		$cacheStoragePathOption = \COption::GetOptionString("wlbl.twigrix", "cache_storage_path");

		if ($cacheStoragePathOption == "") {
			$cacheStoragePath = $documentRoot . BX_PERSONAL_ROOT . "/cache/twig";
		} else {
			$cacheStoragePath = $documentRoot . $cacheStoragePathOption;
		}

		$debugModeOptionValue = \COption::GetOptionString("wlbl.twigrix", "debug_mode");
		$debugMode = ($debugModeOptionValue == "Y") ? true : false;

		$loader = new \Twig_Loader_Filesystem($documentRoot);
		$this->environment = new \Twig_Environment(
			$loader,
			[
				'autoescape' => false,
				'cache' => $cacheStoragePath,
				'debug' => $debugMode
			]
		);

		$this->addExtensions();

		self::$instance = $this;
	}

	/**
	 * Добавляет расширения, в том числе расширение для битрикса,
	 * в котором добавляются нужные глобальные переменные и т.п.
	 */
	private function addExtensions()
	{
		$this->getEnvironment()->addExtension(new \Twig_Extension_Debug());
		$this->getEnvironment()->addExtension(new BitrixExtension());

		$event = new Event('wlbl.twigrix', 'onAddExtensions');
		$event->send();

		foreach ($event->getResults() as $result) {
			if ($result->getType() == EventResult::SUCCESS) {
				foreach ($result->getParameters() as $extension) {
					if ($extension instanceof \Twig_Extension) {
						$this->getEnvironment()->addExtension($extension);
					}
				}
			}
		}
	}

	/**
	 * Выполняет рендеринг шаблона
	 *
	 * @param string $templateFile
	 * @param array $context
	 * @return string
	 */
	public function renderTemplate($templateFile, array $context)
	{
		return $this->getEnvironment()->render($templateFile, $context);
	}

	/**
	 * Очистка кеша шаблонов
	 */
	public function clearCacheFiles()
	{
		$this->getEnvironment()->clearCacheFiles();
	}

	public static function getInstance()
	{
		if (!is_null(self::$instance)) {
			return self::$instance;
		}
		self::$instance = new self();

		return self::$instance;
	}
}
