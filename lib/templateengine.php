<?php
namespace Wlbl\Twigrix;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Configuration;
use Bitrix\Main\Context;
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
		$options = [
			'cache_dir' => '',
			'use_site_id_in_cache' => false,
			'debug' => false,
		];

		$options = array_replace($options, Configuration::getValue('wlbl.twigrix'));

		$documentRoot = Application::getDocumentRoot();
		if (empty($options['cache_dir'])) {
			$cacheStoragePath = $documentRoot . BX_PERSONAL_ROOT . "/cache/twig";
		} else {
			$cacheStoragePath = $documentRoot . $options['cache_dir'];
		}

		if (boolval($options['use_site_id_in_cache'])) {
			$cacheStoragePath .= "/" . Context::getCurrent()->getSite();
		}

		$debugMode = boolval($options['debug']);

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
