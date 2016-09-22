<?php
require_once 'tools/renderTwigTemplate.php';

global $arCustomTemplateEngines;
$arCustomTemplateEngines["twig"] = [
	"templateExt" => ["twig"],
	"function" => "renderTwigTemplate"
];
