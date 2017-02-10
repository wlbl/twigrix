# Twigrix

Модуль подключения шаблонизатора Twig для Битрикс
Основан на https://github.com/HighTechnologiesCenter/twigrix

## Установка

* Загрузить и установить модуль через composer.
* После установки он появится в разделе "Установленные решения".

## Использование

* Для обработки шаблонизатором Twig шаблон и языковые файлы должны иметь расширение `.twig`.
* Настройки задаются в файле .settings.php или .settings_extra.php, пример:
```
'wlbl.twigrix' =>
	[
		'value' =>
			[
				'cache_dir' => '', // путь до папки хранения кеша с ведущим слешом (от корня сайта) (string)
				'use_site_id_in_cache' => false, // влючать в путь до папки с кешем LID сайта (true|false)
				'debug' => false, // включить debug режим (true|false)
			],
	],
```

## Работа с шаблонами

### Переменные Битрикс, передаваемые в Twig-шаблон

* `params` — `$arParams`;
* `result` — `$arResult`;
* `langMessages` — `$arLangMessages`;
* `template` — `$template`;
* `templateFolder` — `$templateFolder`;
* `parentTemplateFolder` — `$parentTemplateFolder`.

### Другие переменные

* `APPLICATION` — `$APPLICATION` (глобальная переменная Битрикс);
* `LANG` — `LANG` (константа Битрикс);
* `POST_FORM_ACTION_URI` — `POST_FORM_ACTION_URI` (константа Битрикс);
* `DEFAULT_TEMPLATE_PATH` — `DEFAULT_TEMPLATE_PATH` (константа, определенная в `classes/general/templating/BitrixTwigExtension.php`);
* `_REQUEST` — `$_REQUEST`;
* `SITE_SERVER_NAME` — `SITE_SERVER_NAME` (глобальная переменная Битрикс).

### Функции Битрикс, доступные в Twig-шаблоне (все функции принимают те же аргументы, что в Битриксе)

* `ShowMessage`;
* `bitrix_sessid_post`;
* `bitrix_sessid_get`;
* `ShowError`;
* `ShowNote`.

### Дополнительные функции

* `IsUserAdmin` (аналогично вызову `$USER->IsAdmin()`);
* `IsUserAuthorized` (аналогично вызову `$USER->IsAuthorized()`).

### Фильтры

* `formatDate` - форматирование даты с помощью функции Битрикс `FormatDateFromDB()`;
* `russianPluralForm` - получение множественной формы слова.
Пример: `{{ 'товар|товара|товаров'|russianPluralForm(2) }}`