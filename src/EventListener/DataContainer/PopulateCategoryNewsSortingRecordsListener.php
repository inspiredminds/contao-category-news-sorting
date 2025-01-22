<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoCategoryNewsSorting\EventListener\DataContainer;

use Codefog\HasteBundle\Model\DcaRelationsModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Haste\Model\Model;

#[AsCallback('tl_category_news_sorting', 'config.onload')]
class PopulateCategoryNewsSortingRecordsListener
{
    public function __construct(private readonly Connection $db)
    {
    }

    public function __invoke(DataContainer $dc): void
    {
        if (!$dc->id) {
            return;
        }

        if (class_exists(Model::class)) {
            $newsIds = Model::getReferenceValues('tl_news', 'categories', $dc->id);
        } else {
            $newsIds = DcaRelationsModel::getReferenceValues('tl_news', 'categories', $dc->id);
        }

        /** @var list<int> $newsIds */
        $newsIds = array_map('intval', $newsIds);

        // Delete any records not belonging to this category anymore
        $this->db->executeQuery('DELETE FROM tl_category_news_sorting WHERE pid = ? AND news NOT IN (?)', [$dc->id, $newsIds], [null, ArrayParameterType::INTEGER]);

        // Add records not existing yet
        /** @var list<int> $existingNewsIds */
        $existingNewsIds = array_map('intval', $this->db->fetchFirstColumn('SELECT news FROM tl_category_news_sorting WHERE pid = ?', [$dc->id]));
        $newNewsIds = array_diff($newsIds, $existingNewsIds);

        if (!$newNewsIds) {
            return;
        }

        $sorting = (int) $this->db->fetchOne('SELECT MAX(sorting) FROM tl_category_news_sorting WHERE pid = ?', [$dc->id]);

        foreach ($newNewsIds as $newsId) {
            if (!\in_array($newsId, $existingNewsIds, true)) {
                $sorting += 128;

                $this->db->insert('tl_category_news_sorting', [
                    'tstamp' => time(),
                    'pid' => $dc->id,
                    'news' => $newsId,
                    'sorting' => $sorting,
                ]);
            }
        }
    }
}
