<?php

namespace Wlbl\Twigrix;

use Bitrix\Main\EventResult;
use Bitrix\Main\Loader;

/**
 * Перехватчики событий.
 *
 * Для каждого события, возникающего в системе, которе необходимо отлавливать, создаётся
 * в данном классе одноимённый метод. Метод должен быть зарегистрирован в системе через установщик модуля.
 */
class EventHandlers
{
	/**
	 * Автоматическое подключение модуля.
	 *
	 * @throws \Bitrix\Main\LoaderException
	 */
	public static function onPageStart()
	{
		Loader::includeModule('wlbl.twigrix');
	}
}
