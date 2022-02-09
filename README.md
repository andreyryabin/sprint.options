# README #

Скачайте код модуля через composer
-------------------------

**composer require andreyryabin/sprint.options**

```
{
  "extra": {
    "installer-paths": {
      "bitrix/modules/{$name}/": ["type:bitrix-module"]
    }
  },
  "require": {
    "andreyryabin/sprint.options": "dev-master"
  },
}

```

Установите модуль через админку (маркетплейс - установленные решения)

Появится файл /bitrix/php_interface/sprint.options.php или /local/php_interface/sprint.options.php

Редактируйте его содержимое на свое усмотрение, пример

Новый формат
```

use Sprint\Options\Builder\Builder;

return (new Builder)
    ->setTitle('Настройки контента')
    ->setSort(60)
    ->addPage('Страница 1')
    ->addTab('О компании')
    ->addOption('EMAIL', [
        'TITLE'   => 'Email компании',
        'DEFAULT' => 'about@example.com',
        'WIDTH'   => '400',
    ])
    ->addOption('OFFICE', [
        'TITLE'   => 'Адрес офиса',
        'DEFAULT' => 'Адрес офиса',
        'WIDTH'   => '600',
        'HEIGHT'  => '100',
    ])
    ->addTab('Общие')
    ->addOption('SELECT1', [
        'TITLE'   => 'Значение из списка',
        'DEFAULT' => 'var2',
        'OPTIONS' => [
            'var1' => 'Вариант 1',
            'var2' => 'Вариант 2',
            'var3' => 'Вариант 3',
            'var4' => 'Вариант 4',
        ],
    ])
    ->addPage('Страница 2')
    ->addTab('Таб 1')
    ->addOption('EMAIL_OFFICE_1', [
        'TITLE'   => 'Email офиса 1',
        'DEFAULT' => 'about1@example.com',
        'WIDTH'   => '400',
    ])
    ->addTab('Таб 2')
    ->addOption('EMAIL_OFFICE_2', [
        'TITLE'   => 'Email офиса 2',
        'DEFAULT' => 'about2@example.com',
        'WIDTH'   => '400',
    ]);
```

Старый формат
```
return array(
    'EMAIL' => array(
        'TITLE' => 'Email',
        'DEFAULT' => 'about@example.com',
        'WIDTH' => '400',
        'TAB' => 'О компании',
    ),

    'OFFICE' => array(
        'TITLE' => 'Адрес офиса',
        'DEFAULT' => 'Адрес офиса',
        'WIDTH' => '600',
        'HEIGHT' => '100',
        'TAB' => 'О компании',
    ),

    'SELECT1' => array(
        'TITLE' => 'Значение из списка',
        'DEFAULT' => 'var2',
        'OPTIONS' => array(
            'var1' => 'Вариант 1',
            'var2' => 'Вариант 2',
            'var3' => 'Вариант 3',
            'var4' => 'Вариант 4',
        ),
    ),
);


```

Подключите модуль в init.php: CModule::IncludeModule("sprint.options");

Используйте метод sprint_options_get в шаблонах и компонентах проекта чтобы вывести нужные значения

```
<?=sprint_options_get('EMAIL')?>
```

Значения параметров можно менять через админку, появится страница (Контент - Настройки контента) /bitrix/admin/sprint_options.php

Измененные параметры можно сбросить кнопкой сброса до значений в файле sprint.options.php (ключ DEFAULT)

![sprint.options.png](https://bitbucket.org/repo/KkE5r9/images/3092309546-sprint.options.png)
