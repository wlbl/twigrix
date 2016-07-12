<?php

class TwigTemplateEngine
{
	/**
	 * Окружение twig
	 *
	 * @var Twig_Environment
	 */
	private static $twigEnvironment;

	public static function initialize($templateRootPath, $cacheStoragePath)
	{

		$debugModeOptionValue = COption::GetOptionString("twigrix", "debug_mode");
		$debugMode = ($debugModeOptionValue == "Y") ? true : false;

		$loader = new Twig_Loader_Filesystem($templateRootPath);
		self::$twigEnvironment = new Twig_Environment(
			$loader,
			[
				'autoescape' => false,
				'cache' => $cacheStoragePath,
				'debug' => $debugMode
			]
		);

		self::addExtensions();

		global $arCustomTemplateEngines;
		$arCustomTemplateEngines["twig"] = [
			"templateExt" => ["twig"],
			"function" => "renderTwigTemplate"
		];
	}

	/**
	 * Добавляет расширения, в том числе расширение для битрикса,
	 * в котором добавляются нужные глобальные переменные и т.п.
	 */
	private static function addExtensions()
	{
		self::$twigEnvironment->addExtension(new Twig_Extension_Debug());
		self::$twigEnvironment->addExtension(new BitrixTwigExtension());
	}

	/**
	 * Выполняет рендеринг шаблона
	 *
	 * @param string $templateFile
	 * @param array $context
	 * @return string
	 */
	public static function renderTemplate($templateFile, array $context)
	{
		return self::$twigEnvironment->render($templateFile, $context);
	}

	/**
	 * Очистка кеша шаблонов
	 */
	public static function clearCacheFiles()
	{
		self::$twigEnvironment->clearCacheFiles();
	}
}

function renderTwigTemplate(
	$templateFile, $arResult, $arParams, $arLangMessages, $templateFolder, $parentTemplateFolder, $template
) {
	echo TwigTemplateEngine::renderTemplate(
		$templateFile,
		[
			'params' => $arParams,
			'result' => $arResult,
			'langMessages' => $arLangMessages,
			'template' => $template,
			'templateFolder' => $templateFolder,
			'parentTemplateFolder' => $parentTemplateFolder,
		]
	);
	$component_epilog = $templateFolder . "/component_epilog.php";

	if (file_exists($_SERVER["DOCUMENT_ROOT"] . $component_epilog)) {
		$component = $template->__component;
		$component->SetTemplateEpilog(
			[
				"epilogFile" => $component_epilog,
				"templateName" => $template->__name,
				"templateFile" => $template->__file,
				"templateFolder" => $template->__folder,
				"templateData" => false,
			]
		);
	}
}