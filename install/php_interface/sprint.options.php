<?php

use Sprint\Options\Builder\Builder;
use Sprint\Options\Custom\CheckboxOption;
use Sprint\Options\Custom\FileOption;
use Sprint\Options\Custom\SelectOption;
use Sprint\Options\Custom\StringOption;
use Sprint\Options\Custom\TextareaOption;
use Sprint\Options\Custom\WysiwygOption;

return (new Builder)
    ->setTitle('Настройки контента')
    ->setSort(60)
    ->addPage('Страница 1')
    ->addTab('О компании')
    ->addCustomOption(
        (new StringOption('EMAIL'))
            ->setTitle('Email компании')
            ->setDefault('about@example.com')
            ->setWidth('400')
    )
    ->addCustomOption(
        (new TextareaOption('OFFICE'))
            ->setTitle('Адрес офиса')
            ->setDefault('Адрес офиса')
            ->setWidth('400')
            ->setHeight('100')
    )
    ->addCustomOption(
        (new WysiwygOption('DESCRIPTION'))
            ->setTitle('Подробная информация о компании')
            ->setHeight('300')
    )
    ->addTab('Общие')
    ->addCustomOption(
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
    ->addCustomOption(
        (new FileOption('PICTURE'))
            ->setTitle('Фото офиса')
            ->setAllowImages(1)
    )
    ->addCustomOption(
        (new FileOption('FILES'))
            ->setTitle('Документы')
            ->setAllowFiles(0)
    )
    ->addCustomOption(
        (new CheckboxOption('SHOW_FILES'))
            ->setTitle('Показывать документы')
    );
