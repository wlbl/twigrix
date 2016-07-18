<?php
//путь вычисляется относительно папки с модулем twigrix
CModule::AddAutoloadClasses(
	'wlbl.twigrix',
	[
		'TwigTemplateEngine' => 'classes/general/templating/TwigTemplateEngine.php',
		'BitrixTwigExtension' => 'classes/general/templating/BitrixTwigExtension.php'
	]
);

// Initialize Twig template engine
$documentRoot = $_SERVER['DOCUMENT_ROOT'];
$cacheStoragePathOption = COption::GetOptionString("wlbl.twigrix", "cache_storage_path");

if ($cacheStoragePathOption == "") {
	$cacheStoragePath = $documentRoot . BX_PERSONAL_ROOT . "/cache/twig";
} else {
	$cacheStoragePath = $documentRoot . $cacheStoragePathOption;
}

TwigTemplateEngine::initialize($documentRoot, $cacheStoragePath);