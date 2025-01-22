<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

$GLOBALS['TL_DCA']['tl_news_category']['list']['operations'] = array_slice($GLOBALS['TL_DCA']['tl_news_category']['list']['operations'], 0, 1, true) + [
    'sorting' => [
        'href' => 'table=tl_category_news_sorting',
        'icon' => 'bundles/contaocategorynewssorting/arrow-down-0-1.svg',
    ],
] + array_slice($GLOBALS['TL_DCA']['tl_news_category']['list']['operations'], 1, count($GLOBALS['TL_DCA']['tl_news_category']['list']['operations']) - 1, true);
