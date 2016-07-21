<?php
use Wlbl\Twigrix\TemplateEngine;

require_once 'tools/renderTwigTemplate.php';

// Initialize Twig template engine
$documentRoot = $_SERVER['DOCUMENT_ROOT'];
$cacheStoragePathOption = COption::GetOptionString("wlbl.twigrix", "cache_storage_path");

if ($cacheStoragePathOption == "") {
	$cacheStoragePath = $documentRoot . BX_PERSONAL_ROOT . "/cache/twig";
} else {
	$cacheStoragePath = $documentRoot . $cacheStoragePathOption;
}

TemplateEngine::initialize($documentRoot, $cacheStoragePath);
