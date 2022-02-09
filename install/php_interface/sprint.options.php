<?php

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
