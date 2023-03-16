<?php

use Sprint\Options\Builder\Builder;
use Sprint\Options\Custom\FileOption;
use Sprint\Options\Custom\SelectOption;
use Sprint\Options\Custom\StringOption;
use Sprint\Options\Custom\TextareaOption;

return (new Builder)
    ->setTitle('Настройки контента')
    ->setSort(60)
    ->addPage('Страница 1')
    ->addTab('О компании')
    ->addCustom(
        (new StringOption('EMAIL'))
            ->setTitle('Email компании')
            ->setDefault('about@example.com')
            ->setWidth('400')
    )
    ->addCustom(
        (new TextareaOption('OFFICE'))
            ->setTitle('Адрес офиса')
            ->setDefault('Адрес офиса')
            ->setWidth('400')
            ->setHeight('100')
    )
    ->addTab('Общие')
    ->addCustom(
        (new SelectOption('SELECT1'))
            ->setTitle('Значение из списка')
            ->setDefault('var2')
            ->setWidth(100)
            ->setOptions([
                'var1' => 'Вариант 1',
                'var2' => 'Вариант 2',
                'var3' => 'Вариант 3',
                'var4' => 'Вариант 4',
            ])
    )
    ->addPage('Страница 2')
    ->addTab('Таб 1')
    ->addCustom(
        (new FileOption('PICTURE'))
            ->setTitle('Фото офиса')
            ->setAllowImages(1)
    )
    ->addCustom(
        (new FileOption('FILES'))
            ->setTitle('Документы')
            ->setAllowFiles(0)
    );
