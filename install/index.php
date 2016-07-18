<?php
IncludeModuleLangFile(__FILE__);

class wlbl_twigrix extends CModule
{
	public $MODULE_ID = "wlbl.twigrix";
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;

	public function __construct()
	{
		$arModuleVersion = [];

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path . "/version.php");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}

		$this->MODULE_NAME = GetMessage("TWIG_INTEGRATION_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("TWIG_INTEGRATION_MODULE_DESC");
		$this->PARTNER_NAME = GetMessage("TWIG_INTEGRATION_MODULE_PARTNER");
		$this->PARTNER_URI = GetMessage("TWIG_INTEGRATION_MODULE_URI");
	}

	public function DoInstall()
	{
		global $APPLICATION;
		RegisterModule("wlbl.twigrix");
	}

	public function DoUninstall()
	{
		global $APPLICATION;
		UnRegisterModule("wlbl.twigrix");
	}

	/**
	 * @return string
	 */
	public static function getDocumentRoot()
	{
		return @$_SERVER["DOCUMENT_ROOT"];
	}
}
