<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

use Contao\DataContainer;
use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_category_news_sorting'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'ptable' => 'tl_news_category',
        'closed' => true,
        'notEditable' => true,
        'notDeletable' => true,
        'notCopyable' => true,
        'notCreatable' => true,
        'doNotCopyRecords' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'pid' => 'index',
            ],
        ],
    ],
    'list' => [
        'sorting' => [
            'mode' => DataContainer::MODE_PARENT,
            'fields' => ['sorting'],
            'headerFields' => ['title'],
        ],
        'label' => [
            'fields' => ['news'],
            'format' => '%s',
        ],
        'operations' => [],
        'global_operations' => [
            'remove' => [
                'href' => 'key=removeCategoryNewsSorting',
                'icon' => 'delete.svg',
            ],
        ],
    ],
    'fields' => [
        'id' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'autoincrement' => true],
        ],
        'tstamp' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
        ],
        'pid' => [
            'foreignKey' => 'tl_news_category.title',
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
        ],
        'sorting' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
        ],
        'news' => [
            'foreignKey' => 'tl_news.headline',
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
        ],
    ],
];
