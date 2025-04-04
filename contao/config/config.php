<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

use InspiredMinds\ContaoCategoryNewsSorting\EventListener\DataContainer\CategoryNewsSortingRemoveCallback;

$GLOBALS['BE_MOD']['content']['news']['tables'][] = 'tl_category_news_sorting';
$GLOBALS['BE_MOD']['content']['news']['removeCategoryNewsSorting'] = [CategoryNewsSortingRemoveCallback::class, '__invoke'];
