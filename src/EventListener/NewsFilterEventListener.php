<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoCategoryNewsSorting\EventListener;

use Codefog\NewsCategoriesBundle\Model\NewsCategoryModel;
use Codefog\NewsCategoriesBundle\NewsCategoriesManager;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Input;
use Doctrine\DBAL\Connection;
use InspiredMinds\ContaoNewsFilterEvent\Event\NewsFilterEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(priority: -2000)]
class NewsFilterEventListener
{
    public function __construct(
        private readonly NewsCategoriesManager $newsCategoriesManager,
        private readonly ContaoFramework $contaoFramework,
        private readonly Connection $db,
    ) {
    }

    public function __invoke(NewsFilterEvent $event): void
    {
        $module = $event->getModule();

        if (!$module->news_enableCategorySorting) {
            return;
        }

        $param = $this->newsCategoriesManager->getParameterName();

        $this->contaoFramework->initialize();

        if (!$categoryAlias = Input::get($param)) {
            return;
        }

        if (!$category = NewsCategoryModel::findByAlias($categoryAlias)) {
            return;
        }

        $newsIds = array_map('intval', $this->db->fetchFirstColumn('SELECT news FROM tl_category_news_sorting WHERE pid = ? ORDER BY sorting ASC', [$category->id]));

        if (!$newsIds) {
            return;
        }

        $order = \sprintf('FIELD (tl_news.id, %s), %s', implode(', ', $newsIds), $event->getOption('order') ?: 'tl_news.date DESC');

        $event->addOption('order', $order, true);
    }
}
