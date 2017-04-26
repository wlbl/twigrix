<?php
namespace Wlbl\Twigrix;

class BitrixExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
	const DEFAULT_TEMPLATE_PATH = "/bitrix/templates/.default";

	public function getName()
	{
		return 'bitrix';
	}

	/**
	 * Возвращает список глобльных переменных, которые будут доступны в шаблоне после добавления данного расширения
	 * @return array
	 */
	public function getGlobals()
	{
		global $APPLICATION;

		return [
			'APPLICATION' => $APPLICATION,
			'LANG' => LANG,
			'POST_FORM_ACTION_URI' => POST_FORM_ACTION_URI,
			'DEFAULT_TEMPLATE_PATH' => self::DEFAULT_TEMPLATE_PATH,
			'_REQUEST' => $_REQUEST,
			'SITE_SERVER_NAME' => SITE_SERVER_NAME,
		];
	}

	/**
	 * Возвращает список функций, которые будут доступны в шаблоне после добавления данного расширения
	 * @return array
	 */
	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('ShowMessage', [$this, 'showMessage'], ['message']),
			new \Twig_SimpleFunction('bitrix_sessid_post', [$this, 'bitrix_sessid_post']),
			new \Twig_SimpleFunction('bitrix_sessid_get', [$this, 'bitrix_sessid_get']),
			new \Twig_SimpleFunction('ShowError', [$this, 'showError'], ['message', 'css_class']),
			new \Twig_SimpleFunction('ShowNote', [$this, 'showNote'], ['message', 'css_class']),
			new \Twig_SimpleFunction('IsUserAdmin', [$this, 'isUserAdmin']),
			new \Twig_SimpleFunction('IsUserAuthorized', [$this, 'isUserAuthorized']),
		];
	}

	/**
	 * Возвращает список фильтров, которые будут доступны в шаблоне после добавления данного расширения
	 * @return array
	 */
	public function getFilters()
	{
		return [
			new \Twig_SimpleFilter('formatDate', [$this, 'formatDate'], ['rawDate', 'format']),
			new \Twig_SimpleFilter('russianPluralForm', [$this, 'russianPluralForm'], ['string', 'count', 'delimiter']),
		];
	}

	//функции, которые используются как функции в твиге
	public function showMessage($message)
	{
		ShowMessage($message);
	}

	public function showError($message, $css_class = "errortext")
	{
		ShowError($message, $css_class);
	}

	public function showNote($message, $css_class = "notetext")
	{
		ShowNote($message, $css_class);
	}

	public function bitrix_sessid_post()
	{
		return bitrix_sessid_post();
	}

	public function bitrix_sessid_get()
	{
		return bitrix_sessid_get();
	}

	public function isUserAdmin()
	{
		global $USER;

		return $USER->IsAdmin();
	}

	public function isUserAuthorized()
	{
		global $USER;

		return $USER->IsAuthorized();
	}

	//функции, которые используются как фильтры в твиге
	public function formatDate($rawDate, $format = 'FULL')
	{
		return FormatDateFromDB($rawDate, $format);
	}

	/**
	 * Получение множественной формы слова в зависимости от числительного перед словом
	 * @param $string строка вида 'товар|товара|товаров'
	 * @param $count числительное
	 * @param string $delimiter разделитель в параметре $string
	 * @return mixed
	 */
	public function russianPluralForm($string, $count, $delimiter = "|")
	{
		list($endWith1, $endWith2to4, $endWith5to9and0) = explode($delimiter, $string);

		if (strlen($count) > 1 && substr($count, strlen($count) - 2, 1) == "1") {
			return $endWith5to9and0;
		} else {
			$lastDigit = intval(substr($count, strlen($count) - 1, 1));
			if ($lastDigit == 0 || ($lastDigit >= 5 && $lastDigit <= 9)) {
				return $endWith5to9and0;
			} elseif ($lastDigit == 1) {
				return $endWith1;
			} else {
				return $endWith2to4;
			}
		}
	}
}