<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoCategoryNewsSorting\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\NewsArchiveModel;
use Contao\NewsModel;
use Contao\StringUtil;

#[AsCallback('tl_category_news_sorting', 'list.label.label')]
class CategoryNewsSortingLabelCallback
{
    public function __invoke(array $row, string $label): string
    {
        if (!$news = NewsModel::findById($row['news'])) {
            return $label;
        }

        if (!$archive = NewsArchiveModel::findById($news->pid)) {
            return $label;
        }

        return \sprintf('%s <span style="color:#999;padding-left:3px">[%s]</span>', strip_tags(StringUtil::stripInsertTags(str_replace('{{br}}', ' ', $news->headline))), $archive->title);
    }
}
